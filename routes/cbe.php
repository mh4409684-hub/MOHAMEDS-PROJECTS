<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Supervisor\SupervisorDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\CBELoginController;

// CBE Login Routes
Route::get('/cbe/login', [CBELoginController::class, 'showLoginForm'])->name('cbe.login');
Route::post('/cbe/login', [CBELoginController::class, 'login'])->name('cbe.login.store');
Route::post('/cbe/logout', [CBELoginController::class, 'logout'])->name('cbe.logout');

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
        Route::post('/student-profile', [ReportController::class, 'studentProfile'])->name('student-profile');
        Route::post('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::post('/field-placement', [ReportController::class, 'fieldPlacement'])->name('field-placement');
        Route::post('/system-statistics', [ReportController::class, 'systemStatistics'])->name('system-statistics');
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Student Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'students'])->name('index');
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
    
    // Field Placements
    Route::get('/field-placements', [AdminDashboardController::class, 'fieldPlacements'])->name('field-placements');
    
    // Reports
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/attendance', [AdminDashboardController::class, 'attendanceReports'])->name('attendance-reports');
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
        Route::get('/{logbookEntry}', [StudentDashboardController::class, 'showLogbookEntry'])->name('show');
        Route::get('/{logbookEntry}/edit', [StudentDashboardController::class, 'editLogbookEntry'])->name('edit');
        Route::put('/{logbookEntry}', [StudentDashboardController::class, 'updateLogbookEntry'])->name('update');
        Route::post('/{logbookEntry}/submit', [StudentDashboardController::class, 'submitLogbookEntry'])->name('submit');
    });

    // Weekly Reports
    Route::get('/weekly-reports', [StudentDashboardController::class, 'weeklyReports'])->name('weekly-reports');
    
    // Class Attendance
    Route::get('/class-attendance', [StudentDashboardController::class, 'classAttendance'])->name('class-attendance');
    Route::post('/class-attendance/mark', [StudentDashboardController::class, 'markAttendanceCode'])->name('mark-attendance');
    
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
        Route::get('/{fieldPlacement}', [SupervisorDashboardController::class, 'showStudent'])->name('show');
        Route::get('/{fieldPlacement}/attendance', [SupervisorDashboardController::class, 'fieldAttendance'])->name('attendance');
    });

    // Logbook Review
    Route::prefix('logbooks')->name('logbooks.')->group(function () {
        Route::get('/pending', [SupervisorDashboardController::class, 'pendingLogbooks'])->name('pending');
        Route::get('/{logbookEntry}', [SupervisorDashboardController::class, 'reviewLogbook'])->name('review');
        Route::post('/{logbookEntry}/approve', [SupervisorDashboardController::class, 'approveLogbook'])->name('approve');
        Route::post('/{logbookEntry}/reject', [SupervisorDashboardController::class, 'rejectLogbook'])->name('reject');
        Route::get('/{fieldPlacement}/history', [SupervisorDashboardController::class, 'logbookHistory'])->name('history');
    });

    // Weekly Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/pending', [SupervisorDashboardController::class, 'pendingReports'])->name('pending');
        Route::get('/{weeklyReport}', [SupervisorDashboardController::class, 'reviewReport'])->name('review');
        Route::post('/{weeklyReport}/approve', [SupervisorDashboardController::class, 'approveReport'])->name('approve');
    });

    // Notifications
    Route::get('/notifications', [SupervisorDashboardController::class, 'notifications'])->name('notifications');
});
