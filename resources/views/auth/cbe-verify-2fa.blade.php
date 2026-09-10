<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBE Portal - Security Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-8 text-white text-center">
                <div class="h-14 w-14 mx-auto rounded-2xl bg-white/20 flex items-center justify-center text-2xl mb-3 shadow-inner">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h1 class="text-2xl font-black">Two-Factor Authentication</h1>
                <p class="text-xs text-blue-100 mt-1">Administrator Sign-in Protection</p>
            </div>

            <!-- Form -->
            <div class="px-6 py-8">
                @if (session('info'))
                    <div class="mb-5 bg-blue-50 border border-blue-200 rounded-xl p-3.5 text-xs text-blue-800 flex items-start">
                        <i class="fa-solid fa-circle-info text-blue-500 mr-2 mt-0.5 text-sm"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

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

                <!-- Last OTP Notification Preview (Very helpful in local environment) -->
                @if(session('cbe_last_otp_preview'))
                    <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 text-center">
                        <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider block mb-1">
                            <i class="fa-solid fa-envelope-open-text mr-1"></i> Dispatched to {{ $user->email }}:
                        </span>
                        <span class="font-mono text-2xl font-black tracking-[0.25em] text-slate-900 bg-white px-4 py-1.5 rounded-xl border border-amber-300 shadow-sm inline-block">
                            {{ session('cbe_last_otp_preview') }}
                        </span>
                        <p class="text-[10px] text-amber-700 mt-1.5">You can also copy this verification code directly.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('cbe.verify-2fa.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="otp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 text-center">
                            Enter 6-Digit Verification Code
                        </label>
                        <input
                            type="text"
                            id="otp"
                            name="otp"
                            maxlength="6"
                            placeholder="------"
                            class="w-full text-center tracking-[0.3em] font-mono text-2xl font-black px-4 py-3 border border-slate-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('otp') border-rose-500 @enderror"
                            required
                            autofocus
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl transition duration-200 shadow-lg shadow-blue-500/20"
                    >
                        Verify & Access Dashboard
                    </button>
                </form>

                <div class="mt-6 flex items-center justify-between text-xs text-slate-500 pt-4 border-t border-slate-100">
                    <form method="POST" action="{{ route('cbe.resend-2fa') }}">
                        @csrf
                        <button type="submit" class="text-blue-600 hover:text-blue-800 font-bold">
                            Resend Code
                        </button>
                    </form>
                    <a href="{{ route('cbe.login') }}" class="hover:text-slate-700">Cancel & Sign Out</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
