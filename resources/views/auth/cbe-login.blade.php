<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CBE E-Logbook System - Login</title>
    
    <!-- PWA & Mobile Web App Meta -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="CBE Portal">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW registration failed:', err));
            });
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-white">
                <h1 class="text-3xl font-bold mb-2">CBE System</h1>
                <p class="text-blue-100">E-Logbook & Attendance Management</p>
            </div>

            <!-- Form -->
            <div class="px-6 py-8">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-700 text-sm font-medium">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('cbe.login.store') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="superadmin@cbe.ac.tz"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-gray-700 font-medium">
                                Password
                            </label>
                            <a href="{{ route('cbe.forgot-password') }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition">
                                Forgot password?
                            </a>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg"
                    >
                        Sign In
                    </button>

                    <!-- Student Self-Registration Link -->
                    <div class="mt-4 pt-4 border-t border-dashed border-gray-200 text-center">
                        <p class="text-xs text-gray-500">
                            Are you a new student without an account?
                        </p>
                        <a href="{{ route('cbe.register') }}" class="inline-flex items-center gap-1.5 mt-1 text-xs font-black text-blue-600 hover:text-blue-800 hover:underline">
                            <i class="fa-solid fa-user-plus"></i> Click Here to Register New Student Account &rarr;
                        </a>
                    </div>

                    <!-- Install Mobile App Banner -->
                    <div id="pwa-install-banner" class="mt-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 text-center">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 text-left">
                                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-sm shadow">
                                    <i class="fa-solid fa-mobile-screen"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800">Tumia Kama App Ya Simu</h4>
                                    <p class="text-[10px] text-slate-500">Install moja kwa moja kwenye simu yako</p>
                                </div>
                            </div>
                            <button id="pwa-install-btn" type="button" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] rounded-lg shadow transition">
                                Pakua App
                            </button>
                        </div>
                    </div>
                </form>

                <script>
                    let deferredPrompt;
                    const installBanner = document.getElementById('pwa-install-banner');
                    const installBtn = document.getElementById('pwa-install-btn');

                    window.addEventListener('beforeinstallprompt', (e) => {
                        e.preventDefault();
                        deferredPrompt = e;
                        installBanner.style.display = 'block';
                    });

                    installBtn.addEventListener('click', async () => {
                        if (deferredPrompt) {
                            deferredPrompt.prompt();
                            const { outcome } = await deferredPrompt.userChoice;
                            deferredPrompt = null;
                        } else {
                            alert("Kuweka app hii kwenye simu yako:\n1. Bonyeza vitone 3 vya juu vya Chrome kwenye simu yako (⋮)\n2. Chagua 'Install App' au 'Add to Home Screen'\n\nItajifungua na kufanya kazi kama app ya kawaida!");
                        }
                    });
                </script>

                <!-- Demo Credentials -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-gray-600 text-xs font-bold uppercase tracking-wider mb-3">Quick Login (1-Click Fill):</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button type="button" onclick="fillLogin('mh4409684@gmail.com', 'mobili2004')" class="text-left p-2.5 rounded-lg border-2 border-blue-400 bg-blue-50/50 hover:bg-blue-100 transition">
                            <span class="block text-xs font-black text-blue-900">👑 Mohamedy (Admin)</span>
                            <span class="block text-[10px] text-blue-700">mh4409684@gmail.com</span>
                        </button>
                        <button type="button" onclick="fillLogin('student@cbe.ac.tz', 'Student@2025')" class="text-left p-2.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-300 transition">
                            <span class="block text-xs font-bold text-slate-800">🎓 Student (Demo)</span>
                            <span class="block text-[10px] text-slate-500">student@cbe.ac.tz</span>
                        </button>
                    </div>
                </div>

                <script>
                    function fillLogin(email, password) {
                        document.getElementById('email').value = email;
                        document.getElementById('password').value = password;
                    }
                </script>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-600 text-sm">
            <p>College of Business Education</p>
            <p>Integrated E-Logbook & Attendance System</p>
        </div>
    </div>
</body>
</html>
