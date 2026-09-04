<?php

namespace App\Services;

use App\Models\ClassSession;
use App\Models\ClassAttendance;
use App\Models\CourseInstructor;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Create a class session with attendance code and QR code
     */
    public function createClassSession(array $data): ClassSession
    {
        $attendanceCode = $this->generateAttendanceCode();
        $qrCode = $this->generateQRCode($attendanceCode);

        $session = ClassSession::create([
            'course_id' => $data['course_id'],
            'section_id' => $data['section_id'],
            'lecturer_staff_id' => $data['lecturer_staff_id'],
            'semester_id' => $data['semester_id'],
            'session_date' => $data['session_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'room' => $data['room'] ?? null,
            'attendance_code' => $attendanceCode,
            'qr_code' => $qrCode,
            'qr_expires_at' => now()->addMinutes(15), // QR expires in 15 minutes
            'status' => 'open',
            'expected_students' => $data['expected_students'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        // Notify students in section
        $this->notifyStudentsAboutSession($session);

        return $session->load('course', 'section', 'lecturer.user', 'semester');
    }

    /**
     * Mark student attendance
     */
    public function markAttendance(ClassSession $session, int $studentId, string $method = 'code'): ClassAttendance
    {
        // Verify QR code expiry if using QR
        if ($method === 'qr' && $session->qr_expires_at && $session->qr_expires_at < now()) {
            throw new \Exception('QR code has expired. Please ask lecturer to generate a new code.');
        }

        $attendance = ClassAttendance::updateOrCreate(
            [
                'class_session_id' => $session->id,
                'student_id' => $studentId,
            ],
            [
                'status' => 'present',
                'marked_at' => now(),
                'marked_via' => $method,
            ]
        );

        return $attendance->load('classSession', 'student.user');
    }

    /**
     * Bulk mark attendance
     */
    public function bulkMarkAttendance(ClassSession $session, array $studentIds, string $method = 'code'): array
    {
        $results = [];

        foreach ($studentIds as $studentId) {
            try {
                $attendance = $this->markAttendance($session, $studentId, $method);
                $results['success'][] = $attendance;
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'student_id' => $studentId,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Mark student as absent
     */
    public function markAbsent(ClassSession $session, int $studentId): ClassAttendance
    {
        $attendance = ClassAttendance::updateOrCreate(
            [
                'class_session_id' => $session->id,
                'student_id' => $studentId,
            ],
            [
                'status' => 'absent',
                'marked_at' => now(),
                'marked_via' => 'manual',
            ]
        );

        return $attendance;
    }

    /**
     * Generate attendance code
     */
    public function generateAttendanceCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate QR code
     */
    public function generateQRCode(string $code): string
    {
        // This would integrate with a QR code library like endroid/qr-code
        // For now, return a placeholder that includes the code
        return 'qr_' . $code . '_' . uniqid();
    }

    /**
     * Regenerate QR code for session
     */
    public function regenerateQRCode(ClassSession $session): ClassSession
    {
        $newCode = $this->generateAttendanceCode();
        $newQR = $this->generateQRCode($newCode);

        $session->update([
            'attendance_code' => $newCode,
            'qr_code' => $newQR,
            'qr_expires_at' => now()->addMinutes(15),
        ]);

        return $session->refresh();
    }

    /**
     * Close class session and finalize attendance
     */
    public function closeClassSession(ClassSession $session): ClassSession
    {
        $session->update([
            'status' => 'closed',
        ]);

        // Get all students in section
        $sectionStudents = $session->section->students()->pluck('id');

        // Mark absent for students not marked present
        $presentStudents = $session->attendances()->pluck('student_id')->toArray();
        $absentStudents = $sectionStudents->diff($presentStudents);

        foreach ($absentStudents as $studentId) {
            $this->markAbsent($session, $studentId);
        }

        return $session->refresh();
    }

    /**
     * Get attendance summary for session
     */
    public function getSessionAttendanceSummary(ClassSession $session): array
    {
        $attendances = $session->attendances()->get();
        $total = $session->section->students()->count();

        return [
            'session' => $session,
            'total_students' => $total,
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'attendance_percentage' => $total > 0 
                ? round(($attendances->where('status', 'present')->count() / $total) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get student attendance for course
     */
    public function getStudentCourseAttendance(int $studentId, int $courseId, int $semesterId): array
    {
        $sessions = ClassSession::where('course_id', $courseId)
            ->where('semester_id', $semesterId)
            ->with('attendances')
            ->get();

        $attendances = [];
        $presentCount = 0;
        $absentCount = 0;

        foreach ($sessions as $session) {
            $attendance = $session->attendances()
                ->where('student_id', $studentId)
                ->first();

            if ($attendance) {
                $attendances[] = [
                    'session_date' => $session->session_date,
                    'status' => $attendance->status,
                    'marked_at' => $attendance->marked_at,
                ];

                if ($attendance->status === 'present') {
                    $presentCount++;
                } elseif ($attendance->status === 'absent') {
                    $absentCount++;
                }
            }
        }

        return [
            'course_id' => $courseId,
            'student_id' => $studentId,
            'total_sessions' => $sessions->count(),
            'present' => $presentCount,
            'absent' => $absentCount,
            'attendance_percentage' => $sessions->count() > 0 
                ? round(($presentCount / $sessions->count()) * 100, 2)
                : 0,
            'attendances' => $attendances,
        ];
    }

    /**
     * Get student overall attendance
     */
    public function getStudentOverallAttendance(int $studentId, int $semesterId): array
    {
        $sessions = ClassSession::where('semester_id', $semesterId)
            ->whereHas('section.students', function ($query) use ($studentId) {
                $query->where('id', $studentId);
            })
            ->with('attendances')
            ->get();

        $presentCount = 0;
        $absentCount = 0;
        $lateCount = 0;

        foreach ($sessions as $session) {
            $attendance = $session->attendances()->where('student_id', $studentId)->first();
            
            if ($attendance) {
                if ($attendance->status === 'present') $presentCount++;
                elseif ($attendance->status === 'absent') $absentCount++;
                elseif ($attendance->status === 'late') $lateCount++;
            }
        }

        $total = $sessions->count();

        return [
            'semester_id' => $semesterId,
            'student_id' => $studentId,
            'total_classes' => $total,
            'present' => $presentCount,
            'absent' => $absentCount,
            'late' => $lateCount,
            'attendance_percentage' => $total > 0
                ? round((($presentCount + $lateCount) / $total) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get lecturer's classes
     */
    public function getLecturerClasses(int $lecturerId, $dateFrom = null, $dateTo = null): Collection
    {
        $query = ClassSession::where('lecturer_staff_id', $lecturerId)
            ->with('course', 'section', 'semester');

        if ($dateFrom && $dateTo) {
            $query->whereBetween('session_date', [$dateFrom, $dateTo]);
        }

        return $query->orderBy('session_date', 'desc')->get();
    }

    /**
     * Get today's classes
     */
    public function getTodaysClasses(int $lecturerId): Collection
    {
        return ClassSession::where('lecturer_staff_id', $lecturerId)
            ->where('session_date', today())
            ->with('course', 'section', 'semester')
            ->orderBy('start_time', 'asc')
            ->get();
    }

    /**
     * Notify students about class session
     */
    private function notifyStudentsAboutSession(ClassSession $session): void
    {
        $students = $session->section->students()->with('user')->get();

        foreach ($students as $student) {
            Notification::create([
                'user_id' => $student->user_id,
                'type' => 'class_attendance_open',
                'title' => 'Attendance Required',
                'message' => 'Attendance for ' . $session->course->name . ' is now open. Use code: ' . $session->attendance_code,
                'related_class_session_id' => $session->id,
                'related_student_id' => $student->id,
            ]);
        }
    }
}
