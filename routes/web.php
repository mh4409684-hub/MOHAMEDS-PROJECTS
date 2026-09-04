<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\FeedbackController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'store'])->name('admin.login.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();

        $pendingTickets = Ticket::whereIn('status', ['new', 'seen', 'in_progress', 'pending_requester'])->count();
        $solvedTickets = Ticket::whereIn('status', ['resolved', 'processed', 'done'])->count();
        $criticalTickets = Ticket::where('priority', 'critical')->count();

        $isAdmin = $user && $user->hasRole('Admin');
        $isStaff = $user && $user->hasRole('Staff Member');

        $roleLabel = $isAdmin ? 'Admin Dashboard' : ($isStaff ? 'Staff Dashboard' : 'Customer Dashboard');

        if ($isAdmin) {
            $tickets = Ticket::with(['category', 'requester'])->latest()->take(6)->get();

            return view('admin-dashboard', compact('pendingTickets', 'solvedTickets', 'criticalTickets', 'roleLabel', 'tickets'));
        }

        return view('dashboard', compact('pendingTickets', 'solvedTickets', 'criticalTickets', 'roleLabel', 'isAdmin', 'isStaff'));
    })->name('dashboard');

    Route::middleware('role:Admin')->group(function () {
        Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::put('feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
    });
});

require __DIR__.'/settings.php';

require __DIR__.'/tickets.php';

require __DIR__.'/cbe.php';

