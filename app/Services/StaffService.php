<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Collection;

class StaffService
{
    /**
     * Create new staff member
     */
    public function createStaff(array $data): Staff
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'registration_number' => $data['registration_number'] ?? 'STAFF-' . uniqid(),
            'campus_id' => $data['campus_id'],
            'phone' => $data['phone'] ?? null,
            'is_active' => true,
        ]);

        $staff = Staff::create([
            'user_id' => $user->id,
            'campus_id' => $data['campus_id'],
            'department_id' => $data['department_id'] ?? null,
            'staff_type' => $data['staff_type'], // lecturer, supervisor, coordinator, admin
            'designation' => $data['designation'] ?? null,
            'employment_date' => $data['employment_date'] ?? now(),
            'employment_status' => 'active',
            'bio' => $data['bio'] ?? null,
            'office_location' => $data['office_location'] ?? null,
        ]);

        $this->assignRoleByStaffType($user, $data['staff_type']);

        // 1. Send Welcome Email via HttpMailService (Resend HTTPS API / Mail fallback)
        try {
            $welcomeMail = new \App\Mail\StaffWelcomeMail($user, $data['password'], $data['staff_type']);
            $html = view('emails.staff-welcome', [
                'user' => $user,
                'temporaryPassword' => $data['password'],
                'staffType' => $data['staff_type'],
                'loginUrl' => url('/cbe/login'),
            ])->render();

            $subject = "CBE Portal - Taarifa ya Kufunguliwa Akaunti: " . ($data['staff_type'] === 'field_supervisor' || $data['staff_type'] === 'supervisor' ? 'Field Supervisor' : 'Staff');
            \App\Services\HttpMailService::send($user->email, $subject, $html);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Could not send staff welcome email: ' . $e->getMessage());
        }

        // 2. Dispatch via WhatsApp if phone number exists
        if (!empty($user->phone)) {
            try {
                \App\Services\WhatsAppService::sendSupervisorWelcome(
                    $user->phone,
                    $user->name,
                    $user->email,
                    $data['password'],
                    ucwords(str_replace('_', ' ', $data['staff_type']))
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('WhatsApp staff welcome dispatch error: ' . $e->getMessage());
            }
        }

        return $staff->load('user', 'campus', 'department');
    }

    /**
     * Update staff member
     */
    public function updateStaff(Staff $staff, array $data): Staff
    {
        $staff->update([
            'designation' => $data['designation'] ?? $staff->designation,
            'employment_status' => $data['employment_status'] ?? $staff->employment_status,
            'bio' => $data['bio'] ?? $staff->bio,
            'office_location' => $data['office_location'] ?? $staff->office_location,
        ]);

        if ($staff->user) {
            $staff->user->update([
                'name' => $data['name'] ?? $staff->user->name,
                'phone' => $data['phone'] ?? $staff->user->phone,
            ]);
        }

        return $staff->refresh()->load('user', 'campus', 'department');
    }

    /**
     * Get staff member with details
     */
    public function getStaff(int $id): ?Staff
    {
        return Staff::with([
            'user',
            'campus',
            'department',
            'fieldSupervisorAssignments.fieldPlacement.student.user',
            'classSessions.course',
            'courseInstructors.course'
        ])->find($id);
    }

    /**
     * Get staff by type
     */
    public function getStaffByType(string $staffType): Collection
    {
        return Staff::where('staff_type', $staffType)
            ->with(['user', 'campus', 'department'])
            ->where('employment_status', 'active')
            ->get();
    }

    /**
     * Get lecturers
     */
    public function getLecturers(int $campusId = null): Collection
    {
        $query = Staff::where('staff_type', 'lecturer')
            ->with(['user', 'campus', 'courseInstructors']);

        if ($campusId) {
            $query->where('campus_id', $campusId);
        }

        return $query->where('employment_status', 'active')->get();
    }

    /**
     * Get field supervisors
     */
    public function getFieldSupervisors(int $campusId = null): Collection
    {
        $query = Staff::where('staff_type', 'field_supervisor')
            ->with(['user', 'campus', 'fieldSupervisorAssignments']);

        if ($campusId) {
            $query->where('campus_id', $campusId);
        }

        return $query->where('employment_status', 'active')->get();
    }

    /**
     * Get field coordinators
     */
    public function getFieldCoordinators(int $campusId = null): Collection
    {
        $query = Staff::where('staff_type', 'field_coordinator')
            ->with(['user', 'campus']);

        if ($campusId) {
            $query->where('campus_id', $campusId);
        }

        return $query->where('employment_status', 'active')->get();
    }

    /**
     * Assign role to user based on staff type
     */
    private function assignRoleByStaffType(User $user, string $staffType): void
    {
        $roles = [
            'lecturer' => 'lecturer',
            'supervisor' => 'field_supervisor',
            'field_supervisor' => 'field_supervisor',
            'coordinator' => 'field_coordinator',
            'field_coordinator' => 'field_coordinator',
            'admin' => 'admin',
        ];

        $role = $roles[$staffType] ?? 'field_supervisor';
        $user->syncRoles([$role]);
    }

    /**
     * Deactivate staff member
     */
    public function deactivateStaff(Staff $staff, string $reason = null): bool
    {
        $staff->update([
            'employment_status' => 'terminated',
        ]);

        $staff->user->update(['is_active' => false]);

        return true;
    }

    /**
     * Get staff assigned to student
     */
    public function getStaffAssignedToStudent($studentId): array
    {
        $fieldSupervisors = Staff::whereHas('fieldSupervisorAssignments', function ($query) use ($studentId) {
            $query->whereHas('fieldPlacement', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            });
        })->get();

        $courseLecturers = Staff::whereHas('courseInstructors', function ($query) use ($studentId) {
            $query->whereHas('courseInstructors.section', function ($q) use ($studentId) {
                $q->whereHas('students', function ($sq) use ($studentId) {
                    $sq->where('id', $studentId);
                });
            });
        })->get();

        return [
            'field_supervisors' => $fieldSupervisors,
            'course_lecturers' => $courseLecturers,
        ];
    }
}
