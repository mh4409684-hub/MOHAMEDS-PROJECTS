<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class StudentService
{
    /**
     * Create a new student
     */
    public function createStudent(array $data): Student
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'registration_number' => $data['registration_number'],
            'campus_id' => $data['campus_id'],
            'phone' => $data['phone'] ?? null,
            'is_active' => true,
        ]);

        $user->assignRole('student');

        $student = Student::create([
            'user_id' => $user->id,
            'programme_id' => $data['programme_id'],
            'section_id' => $data['section_id'],
            'campus_id' => $data['campus_id'],
            'academic_year_id' => $data['academic_year_id'],
            'year_of_study' => $data['year_of_study'],
            'enrollment_status' => 'active',
            'enrollment_date' => now(),
            'notes' => $data['notes'] ?? null,
        ]);

        return $student->load('user', 'programme', 'section', 'campus');
    }

    /**
     * Update student information
     */
    public function updateStudent(Student $student, array $data): Student
    {
        $student->update([
            'year_of_study' => $data['year_of_study'] ?? $student->year_of_study,
            'enrollment_status' => $data['enrollment_status'] ?? $student->enrollment_status,
            'notes' => $data['notes'] ?? $student->notes,
        ]);

        if (isset($data['section_id'])) {
            $student->update(['section_id' => $data['section_id']]);
        }

        if ($student->user) {
            $student->user->update([
                'name' => $data['name'] ?? $student->user->name,
                'phone' => $data['phone'] ?? $student->user->phone,
            ]);
        }

        return $student->refresh()->load('user', 'programme', 'section', 'campus');
    }

    /**
     * Get student with full details
     */
    public function getStudent(int $id): ?Student
    {
        return Student::with([
            'user',
            'programme',
            'section',
            'campus',
            'academicYear',
            'enrollments.course',
            'fieldPlacements',
            'classAttendances'
        ])->find($id);
    }

    /**
     * Get students by programme
     */
    public function getStudentsByProgramme(int $programmeId): Collection
    {
        return Student::where('programme_id', $programmeId)
            ->with(['user', 'section', 'campus'])
            ->get();
    }

    /**
     * Get students by section
     */
    public function getStudentsBySection(int $sectionId): Collection
    {
        return Student::where('section_id', $sectionId)
            ->with(['user', 'programme', 'campus'])
            ->get();
    }

    /**
     * Bulk import students from array
     */
    public function bulkImportStudents(array $students, int $campusId, int $academicYearId): array
    {
        $created = [];
        $errors = [];

        foreach ($students as $index => $data) {
            try {
                $student = $this->createStudent(array_merge($data, [
                    'campus_id' => $campusId,
                    'academic_year_id' => $academicYearId,
                ]));
                $created[] = $student;
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                    'data' => $data,
                ];
            }
        }

        return [
            'created' => $created,
            'errors' => $errors,
            'success_count' => count($created),
            'error_count' => count($errors),
        ];
    }

    /**
     * Deactivate student
     */
    public function deactivateStudent(Student $student, string $reason = null): bool
    {
        $student->update([
            'enrollment_status' => 'withdrawn',
            'notes' => $reason ?? $student->notes,
        ]);

        $student->user->update(['is_active' => false]);

        return true;
    }

    /**
     * Get student dashboard data
     */
    public function getStudentDashboardData(Student $student): array
    {
        $fieldPlacement = $student->fieldPlacements()->where('status', 'active')->first();
        
        $fieldProgress = 0;
        $fieldDaysRemaining = 0;
        if ($fieldPlacement) {
            $fieldProgress = $fieldPlacement->field_progress;
            $fieldDaysRemaining = $fieldPlacement->total_days - $fieldPlacement->days_completed;
        }

        $classAttendances = $student->classAttendances()->get();
        $totalClasses = $classAttendances->count();
        $presentClasses = $classAttendances->where('status', 'present')->count();
        $attendancePercentage = $totalClasses > 0 ? round(($presentClasses / $totalClasses) * 100, 2) : 0;

        $logbookEntries = $fieldPlacement?->logbookEntries()->get() ?? collect();
        $approvedLogbooks = $logbookEntries->where('status', 'approved')->count();
        $pendingLogbooks = $logbookEntries->where('status', 'submitted')->count();

        return [
            'student' => $student,
            'field_progress' => $fieldProgress,
            'days_remaining' => $fieldDaysRemaining,
            'attendance_percentage' => $attendancePercentage,
            'pending_activities' => $pendingLogbooks,
            'approved_activities' => $approvedLogbooks,
            'total_classes' => $totalClasses,
            'classes_attended' => $presentClasses,
        ];
    }
}
