<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Programme;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\HostOrganization;
use App\Models\Section;
use App\Models\Course;
use App\Models\Student;
use App\Models\Staff;
use App\Models\FieldPlacement;
use App\Models\FieldSupervisorAssignment;
use App\Models\LogbookEntry;
use App\Models\FieldAttendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Roles & Permissions
        $this->createRoles();
        $this->createPermissions();
        $this->assignPermissionsToRoles();

        // 2. Base data
        $this->seedCampuses();
        $this->seedAcademicData();
        $this->seedSuperAdminUser();

        // 3. Complete Student, Supervisor & Lecturer test data
        $this->seedUniversityUsersAndPlacements();
    }

    private function createRoles(): void
    {
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'field_supervisor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'lecturer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'field_coordinator', 'guard_name' => 'web']);
    }

    private function createPermissions(): void
    {
        $permissions = [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_students', 'create_students', 'edit_students', 'delete_students',
            'view_staff', 'create_staff', 'edit_staff', 'delete_staff',
            'view_logbooks', 'create_logbook_entry', 'edit_logbook_entry', 'delete_logbook_entry',
            'submit_logbook', 'review_logbook', 'approve_logbook', 'reject_logbook',
            'view_weekly_reports', 'create_weekly_report', 'edit_weekly_report',
            'submit_weekly_report', 'review_weekly_report',
            'view_field_placements', 'create_field_placement', 'edit_field_placement',
            'manage_field_supervisor', 'view_field_attendance', 'record_field_attendance',
            'view_classes', 'create_class_session', 'edit_class_session', 'close_class_session',
            'view_class_attendance', 'mark_attendance', 'generate_attendance_code', 'generate_qr_code',
            'view_reports', 'generate_reports', 'export_reports',
            'manage_campuses', 'manage_departments', 'manage_programmes', 'manage_courses',
            'manage_sections', 'manage_academic_years', 'manage_semesters',
            'view_audit_logs', 'manage_roles_permissions', 'manage_notifications',
            'view_dashboard', 'view_profile', 'edit_profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }

    private function assignPermissionsToRoles(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdminRole->syncPermissions(Permission::all());
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }
    }

    private function seedCampuses(): void
    {
        $campuses = [
            ['name' => 'Dar es Salaam', 'code' => 'DES', 'city' => 'Dar es Salaam', 'phone' => '+255 22 XXX XXXX'],
            ['name' => 'Dodoma', 'code' => 'DOD', 'city' => 'Dodoma', 'phone' => '+255 26 XXX XXXX'],
            ['name' => 'Mwanza', 'code' => 'MWZ', 'city' => 'Mwanza', 'phone' => '+255 28 XXX XXXX'],
            ['name' => 'Mbeya', 'code' => 'MBY', 'city' => 'Mbeya', 'phone' => '+255 25 XXX XXXX'],
        ];

        foreach ($campuses as $campus) {
            Campus::firstOrCreate(['code' => $campus['code']], $campus);
        }
    }

    private function seedAcademicData(): void
    {
        $academicYear = AcademicYear::firstOrCreate(
            ['year' => '2025/2026'],
            [
                'start_date' => now()->startOfYear(),
                'end_date' => now()->addYear()->endOfYear(),
                'is_current' => true,
                'is_active' => true,
            ]
        );

        Semester::firstOrCreate(
            ['academic_year_id' => $academicYear->id, 'semester_number' => 1],
            [
                'name' => 'Semester 1',
                'start_date' => $academicYear->start_date,
                'end_date' => $academicYear->start_date->copy()->addMonths(4),
                'is_active' => true,
            ]
        );

        Semester::firstOrCreate(
            ['academic_year_id' => $academicYear->id, 'semester_number' => 2],
            [
                'name' => 'Semester 2',
                'start_date' => $academicYear->start_date->copy()->addMonths(5),
                'end_date' => $academicYear->end_date,
                'is_active' => false,
            ]
        );

        $campus = Campus::where('code', 'DES')->first();

        if ($campus) {
            $itDept = Department::firstOrCreate(
                ['code' => 'IT', 'campus_id' => $campus->id],
                ['name' => 'Information Technology', 'is_active' => true]
            );

            $prog = Programme::firstOrCreate(
                ['code' => 'BIT', 'department_id' => $itDept->id],
                [
                    'name' => 'Bachelor of Information Technology',
                    'level' => 'Bachelor',
                    'duration_years' => 3,
                    'is_active' => true,
                ]
            );

            // Section
            Section::firstOrCreate(
                ['code' => 'BIT-Y3-A'],
                [
                    'name' => 'BIT Year 3 - Section A',
                    'programme_id' => $prog->id,
                    'campus_id' => $campus->id,
                    'year_level' => 3,
                    'section_letter' => 'A',
                    'capacity' => 60,
                    'is_active' => true,
                ]
            );

            // Course
            Course::firstOrCreate(
                ['code' => 'CS301', 'programme_id' => $prog->id],
                [
                    'name' => 'Enterprise System Development',
                    'credit_hours' => 3,
                    'year_level' => 3,
                    'is_active' => true,
                ]
            );

            // Host Organization with GPS coordinates (Dar es Salaam center coordinates)
            HostOrganization::updateOrCreate(
                ['name' => 'TechCorp Tanzania'],
                [
                    'industry' => 'Information Technology',
                    'address' => 'Bibi Titi Mohamed Rd',
                    'city' => 'Dar es Salaam',
                    'latitude' => -6.816064,
                    'longitude' => 39.280358,
                    'geofence_radius_meters' => 500,
                    'contact_person' => 'John Doe',
                    'phone' => '+255 712 345 678',
                    'email' => 'hr@techcorp.co.tz',
                    'is_active' => true,
                ]
            );

            HostOrganization::updateOrCreate(
                ['name' => 'Banking Solutions Ltd'],
                [
                    'industry' => 'Banking & Finance',
                    'address' => 'Samora Avenue',
                    'city' => 'Dar es Salaam',
                    'latitude' => -6.815200,
                    'longitude' => 39.289000,
                    'geofence_radius_meters' => 300,
                    'contact_person' => 'Jane Smith',
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedSuperAdminUser(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'mh4409684@gmail.com'],
            [
                'name' => 'MOHAMEDY HAMADI MOHAMED',
                'username' => 'mohamedy_admin',
                'password' => bcrypt('mobili2004'),
                'registration_number' => '03.5845.01.02.2025',
                'phone' => '+255 700 000 001',
                'is_active' => true,
            ]
        );

        $superAdmin->syncRoles(['super_admin', 'admin']);
    }

    private function seedUniversityUsersAndPlacements(): void
    {
        $campus = Campus::where('code', 'DES')->first();
        $academicYear = AcademicYear::where('is_current', true)->first();
        $programme = Programme::where('code', 'BIT')->first();
        $section = Section::first();
        $techCorp = HostOrganization::where('name', 'TechCorp Tanzania')->first();

        // 1. Supervisor User
        $supervisorUser = User::firstOrCreate(
            ['email' => 'supervisor@cbe.ac.tz'],
            [
                'name' => 'Dr. Amani Supervisor',
                'username' => 'supervisor',
                'password' => bcrypt('Supervisor@2025'),
                'registration_number' => 'STF-001',
                'campus_id' => $campus?->id,
                'phone' => '+255 754 000 002',
                'is_active' => true,
            ]
        );
        $supervisorUser->syncRoles(['field_supervisor']);

        $staff = Staff::firstOrCreate(
            ['user_id' => $supervisorUser->id],
            [
                'campus_id' => $campus?->id,
                'department_id' => $programme?->department_id,
                'staff_type' => 'supervisor',
                'designation' => 'Senior Field Supervisor',
                'employment_date' => now()->subYears(2),
                'employment_status' => 'active',
            ]
        );

        // 2. Student User
        $studentUser = User::firstOrCreate(
            ['email' => 'student@cbe.ac.tz'],
            [
                'name' => 'Mohamedy Student',
                'username' => 'mohamedy',
                'password' => bcrypt('Student@2025'),
                'registration_number' => 'CBE/BIT/2026/042',
                'campus_id' => $campus?->id,
                'phone' => '+255 788 000 003',
                'is_active' => true,
            ]
        );
        $studentUser->syncRoles(['student']);

        $student = Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'programme_id' => $programme?->id,
                'section_id' => $section?->id,
                'campus_id' => $campus?->id,
                'academic_year_id' => $academicYear?->id,
                'year_of_study' => 3,
                'enrollment_status' => 'active',
                'enrollment_date' => now()->subMonths(6),
            ]
        );

        // 3. Field Placement
        $placement = FieldPlacement::firstOrCreate(
            [
                'student_id' => $student->id,
                'host_organization_id' => $techCorp->id,
                'academic_year_id' => $academicYear->id,
            ],
            [
                'start_date' => now()->subDays(14)->toDateString(),
                'end_date' => now()->addDays(46)->toDateString(),
                'status' => 'active',
                'total_days' => 60,
                'days_completed' => 10,
                'field_progress' => 16.67,
            ]
        );

        // 4. Supervisor Assignment
        FieldSupervisorAssignment::firstOrCreate(
            [
                'field_placement_id' => $placement->id,
                'supervisor_staff_id' => $staff->id,
            ],
            [
                'assigned_date' => now()->subDays(14)->toDateString(),
                'status' => 'active',
            ]
        );

        // 5. Sample Logbook Entries
        LogbookEntry::firstOrCreate(
            [
                'field_placement_id' => $placement->id,
                'activity_date' => now()->subDays(1)->toDateString(),
            ],
            [
                'hours_worked' => 8,
                'activity_description' => 'Configured local development server environment and set up MySQL database schema for employee records.',
                'skills_learned' => 'Database migration, Apache vhost routing, PHP 8.2 configurations.',
                'challenges' => 'Permission denial during file uploads on Ubuntu testing server.',
                'solutions' => 'Set www-data group permissions and configured Laravel storage symlink.',
                'status' => 'submitted',
                'submitted_at' => now()->subDays(1),
            ]
        );

        LogbookEntry::firstOrCreate(
            [
                'field_placement_id' => $placement->id,
                'activity_date' => now()->subDays(2)->toDateString(),
            ],
            [
                'hours_worked' => 8,
                'activity_description' => 'Attended orientation with IT department team lead. Introduced to internal networks and security policies.',
                'skills_learned' => 'Workplace safety, corporate network topology, ticketing systems.',
                'challenges' => 'Initial credential activation delay.',
                'solutions' => 'Liaised with SysAdmin to issue token.',
                'status' => 'approved',
                'submitted_at' => now()->subDays(2),
                'approved_at' => now()->subDays(1),
                'supervisor_comments' => 'Good start. Maintain proactive communication.',
            ]
        );

        // 6. Sample Field Attendance (GPS verified)
        FieldAttendance::firstOrCreate(
            [
                'field_placement_id' => $placement->id,
                'attendance_date' => now()->subDays(1)->toDateString(),
            ],
            [
                'check_in_time' => '08:15:00',
                'check_out_time' => '16:30:00',
                'status' => 'present',
                'latitude' => '-6.816064',
                'longitude' => '39.280358',
                'notes' => 'On-site check-in at TechCorp IT center',
            ]
        );
    }
}
