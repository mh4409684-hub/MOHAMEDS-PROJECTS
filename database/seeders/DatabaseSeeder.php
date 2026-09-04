<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Programme;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\HostOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $this->createRoles();

        // Create permissions
        $this->createPermissions();

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        // Seed base data
        $this->seedCampuses();
        $this->seedAcademicData();
        $this->seedSuperAdminUser();
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
            // User Management
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_students', 'create_students', 'edit_students', 'delete_students',
            'view_staff', 'create_staff', 'edit_staff', 'delete_staff',

            // E-Logbook
            'view_logbooks', 'create_logbook_entry', 'edit_logbook_entry', 'delete_logbook_entry',
            'submit_logbook', 'review_logbook', 'approve_logbook', 'reject_logbook',
            'view_weekly_reports', 'create_weekly_report', 'edit_weekly_report',
            'submit_weekly_report', 'review_weekly_report',

            // Field Management
            'view_field_placements', 'create_field_placement', 'edit_field_placement',
            'manage_field_supervisor', 'view_field_attendance', 'record_field_attendance',

            // Class Attendance
            'view_classes', 'create_class_session', 'edit_class_session', 'close_class_session',
            'view_class_attendance', 'mark_attendance', 'generate_attendance_code', 'generate_qr_code',

            // Reports
            'view_reports', 'generate_reports', 'export_reports',

            // Administration
            'manage_campuses', 'manage_departments', 'manage_programmes', 'manage_courses',
            'manage_sections', 'manage_academic_years', 'manage_semesters',
            'view_audit_logs', 'manage_roles_permissions', 'manage_notifications',

            // General
            'view_dashboard', 'view_profile', 'edit_profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }

    private function assignPermissionsToRoles(): void
    {
        // Super Admin - All permissions
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $superAdminRole->syncPermissions(Permission::all());

        // Admin - All except super admin operations
        $adminRole = Role::where('name', 'admin')->first();
        $adminPermissions = Permission::whereNotIn('name', [])->pluck('id');
        $adminRole->syncPermissions($adminPermissions);

        // Student
        $studentRole = Role::where('name', 'student')->first();
        $studentPermissions = Permission::whereIn('name', [
            'view_dashboard', 'view_profile', 'edit_profile',
            'view_logbooks', 'create_logbook_entry', 'edit_logbook_entry', 'submit_logbook',
            'view_weekly_reports', 'create_weekly_report', 'edit_weekly_report', 'submit_weekly_report',
            'view_field_placements', 'view_field_attendance', 'record_field_attendance',
            'view_classes', 'view_class_attendance', 'mark_attendance',
        ])->pluck('id');
        $studentRole->syncPermissions($studentPermissions);

        // Field Supervisor
        $supervisorRole = Role::where('name', 'field_supervisor')->first();
        $supervisorPermissions = Permission::whereIn('name', [
            'view_dashboard', 'view_profile',
            'view_logbooks', 'review_logbook', 'approve_logbook', 'reject_logbook',
            'view_weekly_reports', 'review_weekly_report',
            'view_field_placements', 'view_field_attendance',
            'view_reports', 'generate_reports',
        ])->pluck('id');
        $supervisorRole->syncPermissions($supervisorPermissions);

        // Lecturer
        $lecturerRole = Role::where('name', 'lecturer')->first();
        $lecturerPermissions = Permission::whereIn('name', [
            'view_dashboard', 'view_profile',
            'view_classes', 'create_class_session', 'edit_class_session', 'close_class_session',
            'view_class_attendance', 'mark_attendance', 'generate_attendance_code', 'generate_qr_code',
            'view_reports', 'generate_reports',
        ])->pluck('id');
        $lecturerRole->syncPermissions($lecturerPermissions);

        // Field Coordinator
        $coordinatorRole = Role::where('name', 'field_coordinator')->first();
        $coordinatorPermissions = Permission::whereIn('name', [
            'view_dashboard', 'view_profile',
            'view_field_placements', 'manage_field_supervisor',
            'view_field_attendance',
            'view_logbooks', 'view_weekly_reports',
            'view_reports', 'generate_reports',
        ])->pluck('id');
        $coordinatorRole->syncPermissions($coordinatorPermissions);
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
        // Create academic years
        $academicYear = AcademicYear::firstOrCreate(
            ['year' => '2025/2026'],
            [
                'start_date' => now()->startOfYear(),
                'end_date' => now()->addYear()->endOfYear(),
                'is_current' => true,
                'is_active' => true,
            ]
        );

        // Create semesters
        Semester::firstOrCreate(
            ['academic_year_id' => $academicYear->id, 'semester_number' => 1],
            [
                'name' => 'Semester 1',
                'start_date' => $academicYear->start_date,
                'end_date' => $academicYear->start_date->addMonths(4),
                'is_active' => true,
            ]
        );

        Semester::firstOrCreate(
            ['academic_year_id' => $academicYear->id, 'semester_number' => 2],
            [
                'name' => 'Semester 2',
                'start_date' => $academicYear->start_date->addMonths(5),
                'end_date' => $academicYear->end_date,
                'is_active' => false,
            ]
        );

        // Get Dar es Salaam campus
        $campus = Campus::where('code', 'DES')->first();

        if ($campus) {
            // Create departments
            $itDept = Department::firstOrCreate(
                ['code' => 'IT', 'campus_id' => $campus->id],
                ['name' => 'Information Technology', 'is_active' => true]
            );

            $businessDept = Department::firstOrCreate(
                ['code' => 'BUS', 'campus_id' => $campus->id],
                ['name' => 'Business', 'is_active' => true]
            );

            // Create programmes
            Programme::firstOrCreate(
                ['code' => 'BIT', 'department_id' => $itDept->id],
                [
                    'name' => 'Bachelor of Information Technology',
                    'level' => 'Bachelor',
                    'duration_years' => 3,
                    'is_active' => true,
                ]
            );

            // Create host organizations
            HostOrganization::firstOrCreate(
                ['name' => 'TechCorp Tanzania'],
                [
                    'industry' => 'Information Technology',
                    'city' => 'Dar es Salaam',
                    'contact_person' => 'John Doe',
                    'is_active' => true,
                ]
            );

            HostOrganization::firstOrCreate(
                ['name' => 'Banking Solutions Ltd'],
                [
                    'industry' => 'Banking',
                    'city' => 'Dar es Salaam',
                    'contact_person' => 'Jane Smith',
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedSuperAdminUser(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@cbe.ac.tz'],
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => bcrypt('SuperAdmin@2025'),
                'registration_number' => 'ADM-001',
                'phone' => '+255 700 000 001',
                'is_active' => true,
            ]
        );

        $superAdmin->assignRole('super_admin');
    }
}
