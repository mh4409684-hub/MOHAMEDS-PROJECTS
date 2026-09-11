<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SystemControl;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemLock
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow static assets, login, logout, and owner control panel
        if ($request->is('cbe/login', 'cbe/logout', 'admin/owner-control*', 'up', 'manifest.json', 'sw.js', 'pwa/*')) {
            return $next($request);
        }

        try {
            $control = SystemControl::instance();

            // If system is locked, only MOHAMEDY (Super Admin) can access
            if ($control && $control->is_system_locked) {
                $user = $request->user();
                if (!$user || $user->email !== 'mh4409684@gmail.com') {
                    return response()->view('errors.system-locked', [
                        'reason' => $control->lock_reason,
                        'owner' => $control->owner_name,
                        'email' => $control->owner_email,
                    ], 503);
                }
            }
        } catch (\Throwable $e) {
            // If table does not exist yet during first boot, continue gracefully
        }

        return $next($request);
    }
}