<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CBE E-Logbook System - Login</title>
    
    <!-- Favicon & PWA Icons -->
    <link rel="icon" type="image/png" href="/mohamedtechpro-logo.png">
    <link rel="apple-touch-icon" href="/mohamedtechpro-logo.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#030712">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="MOHAMEDTECH PRO">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW registration failed:', err));
            });
        }
    </script>
    <style>
        #app-splash-screen {
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }
        #app-splash-screen.fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .splash-rainbow-text {
            background: linear-gradient(90deg, #ff007f, #ff7b00, #ffee00, #00f0ff, #7b00ff, #ff007f);
            background-size: 400% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: rainbow-slide 3.5s linear infinite;
        }
        @keyframes rainbow-slide {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }
        .splash-glow {
            box-shadow: 0 0 50px rgba(59, 130, 246, 0.5), 0 0 100px rgba(236, 72, 153, 0.3);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-950 to-blue-950 min-h-screen flex items-center justify-center p-4">
    <!-- App Startup Splash Screen (Powered by MOHAMEDTECH PRO) -->
    <div id="app-splash-screen" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-slate-950 px-4 text-center select-none">
        <div class="relative flex flex-col items-center max-w-sm w-full">
            <div class="absolute -top-10 w-48 h-48 bg-gradient-to-tr from-blue-600/30 to-pink-600/30 rounded-full blur-3xl -z-10 animate-pulse"></div>

            <div class="relative w-28 h-28 mb-5 rounded-3xl overflow-hidden p-1 bg-gradient-to-tr from-blue-500 via-purple-500 to-amber-400 splash-glow animate-bounce" style="animation-duration: 2.5s;">
                <img src="/mohamedtechpro-logo.png" alt="MOHAMEDTECH PRO" class="w-full h-full object-cover rounded-[22px] bg-slate-900">
            </div>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900 border border-slate-700/80 text-[11px] font-bold text-amber-400 mb-2.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>OFFICIAL SECURE PORTAL</span>
            </div>

            <h2 class="text-xl font-black tracking-wider splash-rainbow-text uppercase">
                POWERED BY MOHAMEDYTECH PRO
            </h2>
            <p class="text-xs text-slate-400 mt-1 mb-6 font-medium">
                CBE E-Logbook & Field Attendance Management
            </p>

            <div class="w-44 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-blue-500 via-indigo-400 to-pink-500 w-full animate-[pulse_1s_ease-in-out_infinite]"></div>
            </div>
            <span class="text-[11px] text-slate-500 mt-3 font-semibold tracking-wide">Inafungua mfumo...</span>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const splash = document.getElementById('app-splash-screen');
                if (splash) {
                    splash.classList.add('fade-out');
                    setTimeout(() => splash.remove(), 600);
                }
            }, 1000);
        });
    </script>

    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-800/40">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-white">
                <h1 class="text-3xl font-bold mb-2">CBE System</h1>
                <p class="text-blue-100">E-Logbook & Attendance Management</p>
            </div>

            <!-- Form -->
            <div class="px-6 py-7">
                @if (session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5 shrink-0"></i>
                        <div>
                            <h4 class="text-sm font-bold text-emerald-900 mb-0.5">Taarifa ya Mafanikio</h4>
                            <p class="text-emerald-800 text-xs leading-relaxed">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-info text-blue-600 text-lg mt-0.5 shrink-0"></i>
                        <p class="text-blue-800 text-xs leading-relaxed">{{ session('info') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-red-600 text-lg mt-0.5 shrink-0"></i>
                        <div>
                            <h4 class="text-sm font-bold text-red-900 mb-0.5">Hitilafu ya Kuingia:</h4>
                            <p class="text-red-800 text-xs leading-relaxed">{{ $errors->first() }}</p>
                        </div>
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
                            placeholder="Weka barua pepe yako (mfano: jina@gmail.com)"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-info"></i> Tafadhali thibitisha umeweka barua pepe sahihi uliyojisajili nayo.
                            </p>
                        @enderror
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

                @if(request()->has('owner') || request()->has('admin_access'))
                <!-- Admin Fast Login (Visible only when requested via ?owner=1) -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-gray-500 text-[11px] font-bold uppercase tracking-wider mb-2.5">
                        <i class="fa-solid fa-shield-halved text-blue-600 mr-1"></i> Authorized Portal Access:
                    </p>
                    <button type="button" onclick="fillLogin('mh4409684@gmail.com', 'mobili2004')" class="w-full text-left p-3 rounded-xl border-2 border-blue-400/80 bg-blue-50/60 hover:bg-blue-100 transition shadow-sm flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-black text-blue-950">👑 MOHAMEDY HAMADI (Portal Owner)</span>
                            <span class="block text-[10px] text-blue-700">mh4409684@gmail.com &bull; Click to Auto-fill Credentials</span>
                        </div>
                        <span class="text-xs font-bold text-blue-600">Fill &rarr;</span>
                    </button>
                </div>

                <script>
                    function fillLogin(email, password) {
                        document.getElementById('email').value = email;
                        document.getElementById('password').value = password;
                    }
                </script>
                @endif
            </div>
        </div>

        <!-- Official Watermark & Ownership Badge -->
        <div class="text-center mt-6 text-gray-400 text-xs space-y-2">
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-slate-900/95 text-white text-[11px] font-semibold border border-slate-700/80 shadow-lg hover:border-amber-400/50 transition">
                <img src="/mohamedtechpro-logo.png" alt="MOHAMEDTECH PRO" class="w-5 h-5 rounded-full object-cover ring-1 ring-amber-400/60 shadow-sm">
                <span>Created & Powered by <strong class="text-amber-400 tracking-wider">mohamedtechpro</strong></span>
            </div>
            <p class="font-bold text-gray-700">College of Business Education &bull; Integrated E-Logbook & Attendance</p>
            <p class="text-[11px] text-gray-400">
                Proprietary Rights Reserved &copy; {{ date('Y') }} MOHAMEDY HAMADI MOHAMED
            </p>
        </div>
    </div>
</body>
</html>
