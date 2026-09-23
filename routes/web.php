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

Route::get('/api/health-check', function () {
    return response()->json(['status' => 'online', 'service' => 'CBE Portal', 'powered_by' => 'MOHAMEDYTECH PRO']);
});

Route::post('/api/ai/ask', [\App\Http\Controllers\AiAssistantController::class, 'ask'])->name('ai.ask');

Route::get('/api/test-email', function (\Illuminate\Http\Request $request) {
    $to = $request->query('to', 'mh4409684@gmail.com');
    try {
        \Illuminate\Support\Facades\Mail::raw("Hii ni barua pepe ya majaribio kutoka CBE Server: " . now()->toDateTimeString(), function ($msg) use ($to) {
            $msg->to($to)->subject('CBE Server Email Test - ' . now()->format('H:i:s'));
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully via Laravel Mailer to ' . $to,
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'class' => get_class($e),
            'trace' => $e->getFile() . ':' . $e->getLine(),
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
        ], 500);
    }
});

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

