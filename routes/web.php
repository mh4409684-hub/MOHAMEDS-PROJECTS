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

Route::get('/mail-diag-check', function () {
    $results = [];
    $ports = [
        'smtp.gmail.com:587' => ['smtp.gmail.com', 587],
        'smtp.gmail.com:465' => ['ssl://smtp.gmail.com', 465],
        'www.google.com:443' => ['ssl://www.google.com', 443],
    ];
    foreach ($ports as $label => [$host, $port]) {
        $errno = 0; $errstr = '';
        $t0 = microtime(true);
        $fp = @fsockopen($host, $port, $errno, $errstr, 4);
        $dt = round(microtime(true) - $t0, 3);
        if ($fp) {
            $results[$label] = "OPEN ($dt s)";
            fclose($fp);
        } else {
            $results[$label] = "BLOCKED ($errstr, $dt s)";
        }
    }
    return response()->json($results);
});

