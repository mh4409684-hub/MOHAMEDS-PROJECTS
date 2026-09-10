<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\CollegeSecurityMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our university records.',
            ])->onlyInput('email');
        }

        // Check if Admin requires 2FA OTP verification
        if ($user->hasRole(['super_admin', 'admin'])) {
            $otp = (string) random_int(100000, 999999);
            $user->update([
                'two_factor_otp' => $otp,
                'two_factor_otp_expires_at' => now()->addMinutes(10),
            ]);

            try {
                Mail::to($user->email)->send(new CollegeSecurityMail(
                    $user,
                    'CBE Portal - Admin Sign-in OTP Verification Code',
                    $otp,
                    '2fa'
                ));
            } catch (\Exception $e) {
                // Log and continue gracefully
                \Log::error('Could not send OTP mail: ' . $e->getMessage());
            }

            session([
                'cbe_2fa_user_id' => $user->id,
                'cbe_2fa_remember' => $request->boolean('remember'),
                'cbe_last_otp_preview' => $otp, // Useful preview for testing
            ]);

            return redirect()->route('cbe.verify-2fa')
                ->with('info', "A 6-digit verification code has been sent to {$user->email} for administrator security verification.");
        }

        // Direct login for student / supervisor
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Show 2FA OTP Verification form
     */
    public function show2faForm()
    {
        if (!session()->has('cbe_2fa_user_id')) {
            return redirect()->route('cbe.login');
        }

        $user = User::find(session('cbe_2fa_user_id'));
        if (!$user) {
            return redirect()->route('cbe.login');
        }

        return view('auth.cbe-verify-2fa', compact('user'));
    }

    /**
     * Verify 2FA OTP
     */
    public function verify2fa(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if (!session()->has('cbe_2fa_user_id')) {
            return redirect()->route('cbe.login');
        }

        $user = User::find(session('cbe_2fa_user_id'));

        if (!$user || $user->two_factor_otp !== $request->input('otp')) {
            return back()->withErrors(['otp' => 'Invalid verification code. Please check your email and try again.']);
        }

        if ($user->two_factor_otp_expires_at && $user->two_factor_otp_expires_at < now()) {
            return back()->withErrors(['otp' => 'Verification code has expired. Please request a new code.']);
        }

        // Clear OTP
        $user->update([
            'two_factor_otp' => null,
            'two_factor_otp_expires_at' => null,
        ]);

        $remember = session('cbe_2fa_remember', false);
        session()->forget(['cbe_2fa_user_id', 'cbe_2fa_remember', 'cbe_last_otp_preview']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Resend 2FA OTP
     */
    public function resend2fa()
    {
        if (!session()->has('cbe_2fa_user_id')) {
            return redirect()->route('cbe.login');
        }

        $user = User::find(session('cbe_2fa_user_id'));
        if (!$user) {
            return redirect()->route('cbe.login');
        }

        $otp = (string) random_int(100000, 999999);
        $user->update([
            'two_factor_otp' => $otp,
            'two_factor_otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new CollegeSecurityMail(
                $user,
                'CBE Portal - New Admin Sign-in OTP Verification Code',
                $otp,
                '2fa'
            ));
        } catch (\Exception $e) {
            \Log::error('Resend OTP error: ' . $e->getMessage());
        }

        session(['cbe_last_otp_preview' => $otp]);

        return back()->with('success', 'A new verification code has been dispatched to your email.');
    }

    /**
     * Show Forgot Password Form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.cbe-forgot-password');
    }

    /**
     * Send Password Reset OTP
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No university account found with this email address.',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        $otp = (string) random_int(100000, 999999);

        $user->update([
            'password_reset_otp' => $otp,
            'password_reset_otp_expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::to($user->email)->send(new CollegeSecurityMail(
                $user,
                'CBE Portal - Password Reset Verification Code',
                $otp,
                'password_reset'
            ));
        } catch (\Exception $e) {
            \Log::error('Could not send reset password mail: ' . $e->getMessage());
        }

        session([
            'cbe_reset_email' => $user->email,
            'cbe_last_reset_preview' => $otp,
        ]);

        return redirect()->route('cbe.reset-password')
            ->with('success', "A password reset code has been sent to {$user->email}.");
    }

    /**
     * Show Reset Password Form
     */
    public function showResetPasswordForm()
    {
        $email = session('cbe_reset_email');
        return view('auth.cbe-reset-password', compact('email'));
    }

    /**
     * Handle Reset Password Submission
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || $user->password_reset_otp !== $request->input('otp')) {
            return back()->withErrors(['otp' => 'Invalid OTP code. Please check your email or request a new code.'])->withInput();
        }

        if ($user->password_reset_otp_expires_at && $user->password_reset_otp_expires_at < now()) {
            return back()->withErrors(['otp' => 'This reset code has expired. Please request a new code.'])->withInput();
        }

        $user->update([
            'password' => bcrypt($request->input('password')),
            'password_reset_otp' => null,
            'password_reset_otp_expires_at' => null,
        ]);

        session()->forget(['cbe_reset_email', 'cbe_last_reset_preview']);

        return redirect()->route('cbe.login')
            ->with('success', 'Your password has been successfully reset! You can now sign in.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cbe.login');
    }

    /**
     * Redirect user based on Spatie roles
     */
    protected function redirectBasedOnRole(User $user)
    {
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
}
