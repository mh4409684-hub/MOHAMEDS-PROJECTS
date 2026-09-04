<?php

namespace App\Services;

use App\Models\Student;
use App\Models\FieldPlacement;
use App\Models\LogbookEntry;
use App\Models\ClassSession;
use App\Models\ClassAttendance;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Generate student profile report
     */
    public function generateStudentReport(Student $student): array
    {
        $fieldPlacement = $student->fieldPlacements()
            ->where('status', 'active')
            ->first();

        $report = [
            'title' => 'Student Profile Report',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'student' => [
                'name' => $student->user->name,
                'registration_number' => $student->user->registration_number,
                'email' => $student->user->email,
                'phone' => $student->user->phone,
                'programme' => $student->programme->name,
                'section' => $student->section->name,
                'year_of_study' => $student->year_of_study,
                'campus' => $student->campus->name,
                'enrollment_status' => $student->enrollment_status,
            ],
            'field_information' => [],
            'attendance' => [],
            'logbook_summary' => [],
        ];

        if ($fieldPlacement) {
            $report['field_information'] = [
                'host_organization' => $fieldPlacement->hostOrganization->name,
                'organization_address' => $fieldPlacement->hostOrganization->address,
                'start_date' => $fieldPlacement->start_date->format('Y-m-d'),
                'end_date' => $fieldPlacement->end_date->format('Y-m-d'),
                'total_days' => $fieldPlacement->total_days,
                'days_completed' => $fieldPlacement->days_completed,
                'field_progress' => $fieldPlacement->field_progress . '%',
                'status' => $fieldPlacement->status,
            ];

            // Field attendance
            $attendances = $fieldPlacement->fieldAttendances()->get();
            $report['field_attendance'] = [
                'total_days' => $fieldPlacement->total_days,
                'present_days' => $attendances->where('status', 'present')->count(),
                'absent_days' => $attendances->where('status', 'absent')->count(),
                'late_days' => $attendances->where('status', 'late')->count(),
            ];

            // Logbook summary
            $entries = $fieldPlacement->logbookEntries()->get();
            $report['logbook_summary'] = [
                'total_entries' => $entries->count(),
                'approved' => $entries->where('status', 'approved')->count(),
                'pending' => $entries->where('status', 'submitted')->count(),
                'rejected' => $entries->where('status', 'rejected')->count(),
                'total_hours' => $entries->sum('hours_worked'),
            ];
        }

        return $report;
    }

    /**
     * Generate attendance report for course/section
     */
    public function generateAttendanceReport(int $courseId, int $sectionId, $startDate = null, $endDate = null): array
    {
        $query = ClassSession::where('course_id', $courseId)
            ->where('section_id', $sectionId)
            ->with('course', 'section', 'attendances.student.user');

        if ($startDate && $endDate) {
            $query->whereBetween('session_date', [$startDate, $endDate]);
        }

        $sessions = $query->orderBy('session_date', 'asc')->get();
        $students = $sessions->first()?->section->students()->get() ?? collect();

        $report = [
            'title' => 'Class Attendance Report',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'course' => $sessions->first()?->course->name,
            'section' => $sessions->first()?->section->name,
            'period' => $startDate ? "{$startDate} to {$endDate}" : 'All time',
            'total_sessions' => $sessions->count(),
            'student_attendance' => [],
        ];

        foreach ($students as $student) {
            $attendances = [];
            $present = 0;

            foreach ($sessions as $session) {
                $att = $session->attendances()->where('student_id', $student->id)->first();
                $status = $att?->status ?? 'unmarked';
                $attendances[] = [
                    'date' => $session->session_date->format('Y-m-d'),
                    'status' => $status,
                ];
                if ($status === 'present') {
                    $present++;
                }
            }

            $report['student_attendance'][] = [
                'name' => $student->user->name,
                'registration_number' => $student->user->registration_number,
                'total_present' => $present,
                'total_sessions' => $sessions->count(),
                'percentage' => $sessions->count() > 0 ? round(($present / $sessions->count()) * 100, 2) : 0,
                'details' => $attendances,
            ];
        }

        return $report;
    }

    /**
     * Generate field placement report
     */
    public function generateFieldPlacementReport(FieldPlacement $placement): array
    {
        $student = $placement->student;
        $supervisor = $placement->supervisorAssignments()
            ->where('status', 'active')
            ->first()?->supervisor;

        $logbookEntries = $placement->logbookEntries()
            ->orderBy('activity_date', 'asc')
            ->get();

        $fieldAttendances = $placement->fieldAttendances()
            ->orderBy('attendance_date', 'asc')
            ->get();

        return [
            'title' => 'Field Placement Report',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'student_information' => [
                'name' => $student->user->name,
                'registration_number' => $student->user->registration_number,
                'programme' => $student->programme->name,
                'email' => $student->user->email,
            ],
            'placement_information' => [
                'host_organization' => $placement->hostOrganization->name,
                'address' => $placement->hostOrganization->address,
                'start_date' => $placement->start_date->format('Y-m-d'),
                'end_date' => $placement->end_date->format('Y-m-d'),
                'total_days' => $placement->total_days,
                'days_completed' => $placement->days_completed,
                'progress' => $placement->field_progress,
                'status' => $placement->status,
            ],
            'supervisor_information' => $supervisor ? [
                'name' => $supervisor->user->name,
                'designation' => $supervisor->designation,
                'phone' => $supervisor->user->phone,
                'email' => $supervisor->user->email,
            ] : null,
            'attendance_summary' => [
                'total_days' => $fieldAttendances->count(),
                'present' => $fieldAttendances->where('status', 'present')->count(),
                'absent' => $fieldAttendances->where('status', 'absent')->count(),
                'late' => $fieldAttendances->where('status', 'late')->count(),
                'percentage' => $fieldAttendances->count() > 0 
                    ? round((($fieldAttendances->where('status', 'present')->count() + $fieldAttendances->where('status', 'late')->count()) / $fieldAttendances->count()) * 100, 2)
                    : 0,
            ],
            'logbook_summary' => [
                'total_entries' => $logbookEntries->count(),
                'approved' => $logbookEntries->where('status', 'approved')->count(),
                'pending' => $logbookEntries->where('status', 'submitted')->count(),
                'rejected' => $logbookEntries->where('status', 'rejected')->count(),
                'total_hours' => $logbookEntries->sum('hours_worked'),
            ],
            'logbook_entries' => $logbookEntries->map(function ($entry) {
                return [
                    'date' => $entry->activity_date->format('Y-m-d'),
                    'activity' => $entry->activity_description,
                    'status' => $entry->status,
                    'hours' => $entry->hours_worked,
                ];
            })->toArray(),
        ];
    }

    /**
     * Generate system statistics report
     */
    public function generateSystemStatistics(): array
    {
        return [
            'title' => 'System Statistics Report',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'students' => [
                'total' => Student::count(),
                'active' => Student::where('enrollment_status', 'active')->count(),
                'graduated' => Student::where('enrollment_status', 'graduated')->count(),
                'withdrawn' => Student::where('enrollment_status', 'withdrawn')->count(),
            ],
            'staff' => [
                'total' => \App\Models\Staff::count(),
                'lecturers' => \App\Models\Staff::where('staff_type', 'lecturer')->count(),
                'supervisors' => \App\Models\Staff::where('staff_type', 'field_supervisor')->count(),
                'coordinators' => \App\Models\Staff::where('staff_type', 'field_coordinator')->count(),
            ],
            'field_placements' => [
                'total' => FieldPlacement::count(),
                'active' => FieldPlacement::where('status', 'active')->count(),
                'completed' => FieldPlacement::where('status', 'completed')->count(),
                'suspended' => FieldPlacement::where('status', 'suspended')->count(),
            ],
            'logbooks' => [
                'total' => LogbookEntry::count(),
                'approved' => LogbookEntry::where('status', 'approved')->count(),
                'submitted' => LogbookEntry::where('status', 'submitted')->count(),
                'rejected' => LogbookEntry::where('status', 'rejected')->count(),
            ],
            'attendance' => [
                'total_sessions' => ClassSession::count(),
                'total_records' => ClassAttendance::count(),
            ],
        ];
    }
}
