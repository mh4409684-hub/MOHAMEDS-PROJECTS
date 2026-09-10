<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBE Portal - Enter New Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-white text-center">
                <div class="h-12 w-12 mx-auto rounded-2xl bg-white/20 flex items-center justify-center text-xl mb-3 shadow-inner">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h1 class="text-2xl font-black">Set New Password</h1>
                <p class="text-xs text-blue-100 mt-1">Verify OTP & Update Password</p>
            </div>

            <div class="px-6 py-8">
                @if (session('success'))
                    <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 text-xs text-emerald-800 flex items-start">
                        <i class="fa-solid fa-circle-check text-emerald-500 mr-2 mt-0.5 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 bg-rose-50 border border-rose-200 rounded-xl p-3.5 text-xs text-rose-800 flex items-start">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 mr-2 mt-0.5 text-sm"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif



                <form method="POST" action="{{ route('cbe.reset-password.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('email', $email) }}"
                            required
                        >
                    </div>

                    <div>
                        <label for="otp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            6-Digit Verification Code (OTP)
                        </label>
                        <input
                            type="text"
                            id="otp"
                            name="otp"
                            maxlength="6"
                            placeholder="------"
                            class="w-full text-center tracking-[0.25em] font-mono text-xl font-black px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            New Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            placeholder="At least 6 characters"
                            required
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Confirm New Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            placeholder="Repeat new password"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition duration-200 shadow-md hover:shadow-lg"
                    >
                        Save & Reset Password
                    </button>
                </form>

                <div class="mt-6 text-center text-xs text-slate-500 pt-4 border-t border-slate-100">
                    <a href="{{ route('cbe.login') }}" class="text-blue-600 hover:text-blue-800 font-bold">
                        Back to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
