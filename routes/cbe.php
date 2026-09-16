<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Supervisor\SupervisorDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\CBELoginController;

// CBE Authentication & Security Routes
Route::get('/cbe/login', [CBELoginController::class, 'showLoginForm'])->name('cbe.login');
Route::post('/cbe/login', [CBELoginController::class, 'login'])->name('cbe.login.store');
Route::post('/cbe/logout', [CBELoginController::class, 'logout'])->name('cbe.logout');

// Student Self-Registration (Only Students can self-register)
Route::get('/cbe/register', [CBELoginController::class, 'showRegisterForm'])->name('cbe.register');
Route::post('/cbe/register', [CBELoginController::class, 'register'])->name('cbe.register.store');

// 2FA Admin Verification Routes
Route::get('/cbe/verify-2fa', [CBELoginController::class, 'show2faForm'])->name('cbe.verify-2fa');
Route::post('/cbe/verify-2fa', [CBELoginController::class, 'verify2fa'])->name('cbe.verify-2fa.store');
Route::post('/cbe/resend-2fa', [CBELoginController::class, 'resend2fa'])->name('cbe.resend-2fa');

// Forgot Password & Reset Routes
Route::get('/cbe/forgot-password', [CBELoginController::class, 'showForgotPasswordForm'])->name('cbe.forgot-password');
Route::post('/cbe/forgot-password', [CBELoginController::class, 'sendResetOtp'])->name('cbe.forgot-password.store');
Route::get('/cbe/reset-password', [CBELoginController::class, 'showResetPasswordForm'])->name('cbe.reset-password');
Route::post('/cbe/reset-password', [CBELoginController::class, 'resetPassword'])->name('cbe.reset-password.store');

Route::middleware(['auth'])->group(function () {
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
        Route::post('/clear-old', [NotificationController::class, 'clearOld'])->name('clear-old');
        Route::delete('/{notification}', [NotificationController::class, 'delete'])->name('delete');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/student-profile', [ReportController::class, 'studentProfile'])->name('student-profile');
        Route::match(['get', 'post'], '/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::match(['get', 'post'], '/field-placement', [ReportController::class, 'fieldPlacement'])->name('field-placement');
        Route::match(['get', 'post'], '/system-statistics', [ReportController::class, 'systemStatistics'])->name('system-statistics');
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Student Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'students'])->name('index');
        Route::get('/pending', [AdminDashboardController::class, 'pendingStudents'])->name('pending');
        Route::post('/{student}/approve', [AdminDashboardController::class, 'approveStudent'])->name('approve');
        Route::post('/{student}/reject', [AdminDashboardController::class, 'rejectStudent'])->name('reject');
        Route::get('/create', [AdminDashboardController::class, 'createStudentForm'])->name('create');
        Route::post('/', [AdminDashboardController::class, 'storeStudent'])->name('store');
        Route::get('/{student}', [AdminDashboardController::class, 'showStudent'])->name('show');
        Route::get('/{student}/edit', [AdminDashboardController::class, 'editStudent'])->name('edit');
        Route::put('/{student}', [AdminDashboardController::class, 'updateStudent'])->name('update');
    });

    // Staff Management
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'staff'])->name('index');
        Route::get('/create', [AdminDashboardController::class, 'createStaffForm'])->name('create');
        Route::post('/', [AdminDashboardController::class, 'storeStaff'])->name('store');
        Route::get('/{staff}', [AdminDashboardController::class, 'showStaff'])->name('show');
    });

    // Academic Management
    Route::get('/academic-settings', [AdminDashboardController::class, 'academicSettings'])->name('academic-settings');
    
    // Field Placements & Supervisor Assignment
    Route::get('/field-placements', [AdminDashboardController::class, 'fieldPlacements'])->name('field-placements');
    Route::get('/field-placements/create', [AdminDashboardController::class, 'createPlacementForm'])->name('field-placements.create');
    Route::post('/field-placements', [AdminDashboardController::class, 'storePlacement'])->name('field-placements.store');
    Route::post('/field-placements/{placement}/assign-supervisor', [AdminDashboardController::class, 'assignSupervisorToPlacement'])->name('field-placements.assign-supervisor');
    
    // Reports
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/attendance', [AdminDashboardController::class, 'attendanceReports'])->name('attendance-reports');

    // Super Admin Ownership & Server Kill-Switch Control (Mohamedy Only)
    Route::get('/owner-control', [AdminDashboardController::class, 'ownerControl'])->name('owner-control');
    Route::post('/owner-control', [AdminDashboardController::class, 'updateOwnerControl'])->name('owner-control.update');
    Route::get('/test-email', [AdminDashboardController::class, 'testEmail'])->name('test-email');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [StudentDashboardController::class, 'updateProfile'])->name('update-profile');
    
    // E-Logbook
    Route::prefix('elogbook')->name('elogbook.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'eLogbook'])->name('index');
        Route::get('/create', [StudentDashboardController::class, 'createLogbookEntry'])->name('create');
        Route::post('/', [StudentDashboardController::class, 'storeLogbookEntry'])->name('store');
        Route::get('/{entry}', [StudentDashboardController::class, 'showLogbookEntry'])->name('show');
        Route::get('/{entry}/edit', [StudentDashboardController::class, 'editLogbookEntry'])->name('edit');
        Route::put('/{entry}', [StudentDashboardController::class, 'updateLogbookEntry'])->name('update');
        Route::post('/{entry}/submit', [StudentDashboardController::class, 'submitLogbookEntry'])->name('submit');
    });

    // Weekly Reports
    Route::get('/weekly-reports', [StudentDashboardController::class, 'weeklyReports'])->name('weekly-reports');
    
    // Class Attendance
    Route::get('/class-attendance', [StudentDashboardController::class, 'classAttendance'])->name('class-attendance');
    Route::post('/class-attendance/mark', [StudentDashboardController::class, 'markAttendanceCode'])->name('mark-attendance');
    
    // Field Placement Attendance (GPS)
    Route::get('/field-attendance', [StudentDashboardController::class, 'fieldAttendance'])->name('field-attendance');
    Route::post('/field-attendance/checkin', [StudentDashboardController::class, 'checkInFieldAttendance'])->name('field-attendance.checkin');

    // Field Placement Details & Student Self-Application
    Route::get('/field-placement', [StudentDashboardController::class, 'fieldPlacementDetails'])->name('field-placement');
    Route::get('/field-placement/apply', [StudentDashboardController::class, 'showFieldApplicationForm'])->name('field-placement.apply');
    Route::post('/field-placement/apply', [StudentDashboardController::class, 'storeFieldApplication'])->name('field-placement.store-apply');

    // Notifications
    Route::get('/notifications', [StudentDashboardController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/mark-read', [StudentDashboardController::class, 'markNotificationRead'])->name('mark-notification-read');
});

// Supervisor Routes
Route::middleware(['auth', 'role:field_supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');
    
    // Assigned Students
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [SupervisorDashboardController::class, 'assignedStudents'])->name('index');
        Route::get('/{placement}', [SupervisorDashboardController::class, 'showStudent'])->name('show');
        Route::get('/{placement}/attendance', [SupervisorDashboardController::class, 'fieldAttendance'])->name('attendance');
    });

    // Logbook Review
    Route::prefix('logbooks')->name('logbooks.')->group(function () {
        Route::get('/pending', [SupervisorDashboardController::class, 'pendingLogbooks'])->name('pending');
        Route::get('/{entry}', [SupervisorDashboardController::class, 'reviewLogbook'])->name('review');
        Route::post('/{entry}/approve', [SupervisorDashboardController::class, 'approveLogbook'])->name('approve');
        Route::post('/{entry}/reject', [SupervisorDashboardController::class, 'rejectLogbook'])->name('reject');
        Route::get('/{placement}/history', [SupervisorDashboardController::class, 'logbookHistory'])->name('history');
    });

    // Weekly Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/pending', [SupervisorDashboardController::class, 'pendingReports'])->name('pending');
        Route::get('/{report}', [SupervisorDashboardController::class, 'reviewReport'])->name('review');
        Route::post('/{report}/approve', [SupervisorDashboardController::class, 'approveReport'])->name('approve');
    });

    // Notifications
    Route::get('/notifications', [SupervisorDashboardController::class, 'notifications'])->name('notifications');
});
