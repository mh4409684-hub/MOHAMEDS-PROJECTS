<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\FeedbackController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole(['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        } elseif ($user->hasRole('field_supervisor')) {
            return redirect()->route('supervisor.dashboard');
        }
        return redirect()->route('cbe.login');
    }
    return redirect()->route('cbe.login');
})->name('home');

Route::get('admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'store'])->name('admin.login.store');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole(['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        } elseif ($user->hasRole('field_supervisor')) {
            return redirect()->route('supervisor.dashboard');
        }

        return redirect()->route('cbe.login');
    })->name('dashboard');

    Route::middleware('role:Admin')->group(function () {
        Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::put('feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
    });
});

require __DIR__.'/settings.php';

require __DIR__.'/tickets.php';

require __DIR__.'/cbe.php';

