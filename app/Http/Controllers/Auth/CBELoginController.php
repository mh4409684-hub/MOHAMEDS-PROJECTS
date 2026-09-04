<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CBELoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.cbe-login');
    }

    /**
     * Handle the login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // Redirect based on role
            if ($user->hasRole(['super_admin', 'admin'])) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('student')) {
                return redirect()->route('student.dashboard');
            } elseif ($user->hasRole('field_supervisor')) {
                return redirect()->route('supervisor.dashboard');
            } elseif ($user->hasRole('lecturer')) {
                return redirect()->route('lecturer.dashboard');
            } elseif ($user->hasRole('field_coordinator')) {
                return redirect()->route('coordinator.dashboard');
            }
            
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
