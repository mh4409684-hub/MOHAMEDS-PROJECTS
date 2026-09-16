<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'CBE E-Logbook & Attendance System' }}</title>
    
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW registration failed:', err));
            });
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .bg-pattern {
            background-color: #0f172a;
            background-image: radial-gradient(rgba(59, 130, 246, 0.15) 1px, transparent 0);
            background-size: 24px 24px;
        }

        /* App Splash Screen Animations */
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
<body class="h-full flex flex-col font-sans text-slate-800 antialiased">
    <!-- App Startup Splash Screen (Powered by MOHAMEDTECH PRO) -->
    <div id="app-splash-screen" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-slate-950 px-4 text-center select-none">
        <div class="relative flex flex-col items-center max-w-sm w-full">
            <!-- Glow circle background -->
            <div class="absolute -top-10 w-48 h-48 bg-gradient-to-tr from-blue-600/30 to-pink-600/30 rounded-full blur-3xl -z-10 animate-pulse"></div>

            <!-- MohamedTechPro Logo -->
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

            <!-- Loading indicator -->
            <div class="w-44 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-blue-500 via-indigo-400 to-pink-500 w-full animate-[pulse_1s_ease-in-out_infinite]"></div>
            </div>
            <span class="text-[11px] text-slate-500 mt-3 font-semibold tracking-wide">Inafungua akaunti yako...</span>
        </div>
    </div>

    <script>
        // Dismiss splash screen quickly once page DOM is ready
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

    <!-- Navbar -->
    <header class="sticky top-0 z-40 bg-slate-900 text-white shadow-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/30 text-white">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <span class="text-lg font-bold tracking-tight text-white group-hover:text-blue-400 transition">CBE Portal</span>
                            <span class="block text-xs text-blue-300 font-medium">GPS Logbook & Attendance</span>
                        </div>
                    </a>
                </div>

                <!-- Center Nav based on role -->
                <nav class="hidden md:flex items-center space-x-1">
                    @php $user = auth()->user(); @endphp
                    @if($user && $user->hasRole(['super_admin', 'admin']))
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-gauge mr-1.5"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.students.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-user-graduate mr-1.5"></i> Students
                        </a>
                        <a href="{{ route('admin.staff.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.staff.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-chalkboard-user mr-1.5"></i> Staff
                        </a>
                        <a href="{{ route('admin.field-placements') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.field-placements*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-briefcase mr-1.5"></i> Placements
                        </a>
                        <a href="{{ route('admin.reports') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reports*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-chart-pie mr-1.5"></i> Reports
                        </a>
                        @if($user->email === 'mh4409684@gmail.com')
                            <a href="{{ route('admin.owner-control') }}" class="px-3 py-2 rounded-lg text-sm font-bold {{ request()->routeIs('admin.owner-control*') ? 'bg-amber-500 text-slate-950' : 'text-amber-400 hover:bg-amber-400/10 hover:text-amber-300' }} border border-amber-500/30 transition">
                                <i class="fa-solid fa-crown mr-1.5"></i> Owner Console
                            </a>
                        @endif
                    @elseif($user && $user->hasRole('student'))
                        <a href="{{ route('student.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('student.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-gauge mr-1.5"></i> Overview
                        </a>
                        <a href="{{ route('student.field-attendance') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('student.field-attendance*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-location-dot mr-1.5 text-emerald-400"></i> GPS Attendance
                        </a>
                        <a href="{{ route('student.elogbook.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('student.elogbook.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-book-open mr-1.5"></i> E-Logbook
                        </a>
                        <a href="{{ route('student.weekly-reports') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('student.weekly-reports*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-calendar-check mr-1.5"></i> Weekly Reports
                        </a>
                        <a href="{{ route('student.class-attendance') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('student.class-attendance*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-qrcode mr-1.5"></i> Class Attendance
                        </a>
                    @elseif($user && $user->hasRole('field_supervisor'))
                        <a href="{{ route('supervisor.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('supervisor.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-gauge mr-1.5"></i> Dashboard
                        </a>
                        <a href="{{ route('supervisor.students.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('supervisor.students.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-users mr-1.5"></i> My Students
                        </a>
                        <a href="{{ route('supervisor.logbooks.pending') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('supervisor.logbooks.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-clipboard-check mr-1.5"></i> Pending Logbooks
                        </a>
                        <a href="{{ route('supervisor.reports.pending') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('supervisor.reports.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                            <i class="fa-solid fa-file-signature mr-1.5"></i> Reports
                        </a>
                    @endif
                </nav>

                <!-- User dropdown / Logout -->
                <div class="flex items-center space-x-4">
                    @if(auth()->check())
                        <div class="flex items-center space-x-3">
                            <div class="hidden sm:block text-right">
                                <div class="text-sm font-semibold text-white">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-blue-300 capitalize">{{ auth()->user()->roles->first()?->name ?? 'User' }}</div>
                            </div>
                            <form method="POST" action="{{ route('cbe.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg bg-slate-800 hover:bg-rose-600 text-slate-300 hover:text-white transition shadow-sm" title="Log Out">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('cbe.login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Sign In
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Alert Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4">
        @php
            $sysCtrl = \App\Models\SystemControl::instance();
        @endphp
        @if($sysCtrl->system_announcement)
            <div class="mb-4 rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50 to-blue-50 p-4 text-indigo-950 flex items-center shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center mr-3 shrink-0 shadow-sm">
                    <i class="fa-solid fa-bullhorn text-xs"></i>
                </div>
                <div class="text-xs font-semibold">
                    {{ $sysCtrl->system_announcement }}
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 flex items-center shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg mr-3"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800 flex items-start shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg mr-3 mt-0.5"></i>
                <div class="text-sm font-medium">
                    {{ session('error') ?? $errors->first() }}
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800 flex items-center shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-lg mr-3"></i>
                <span class="text-sm font-medium">{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-4 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sky-800 flex items-center shadow-sm">
                <i class="fa-solid fa-circle-info text-sky-500 text-lg mr-3"></i>
                <span class="text-sm font-medium">{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content with Subtle Background Watermark -->
    <div class="relative flex-1 flex flex-col">
        <!-- Subtle Faint Watermark of MOHAMEDTECH PRO Logo in background -->
        <div class="pointer-events-none fixed inset-0 flex items-center justify-center z-0 opacity-[0.035] overflow-hidden select-none" aria-hidden="true">
            <img src="/mohamedtechpro-logo.png" alt="Watermark" class="w-[580px] h-[580px] object-contain filter grayscale transform rotate-[-12deg]">
        </div>

        <main class="relative z-10 flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-6">
            {{ $slot }}
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 space-y-2">
        <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-900 text-white text-[11px] font-semibold border border-slate-800 shadow-sm hover:border-amber-400/50 transition">
            <img src="/mohamedtechpro-logo.png" alt="MOHAMEDTECH PRO" class="w-5 h-5 rounded-full object-cover ring-1 ring-amber-400/60 shadow-sm">
            <span>Created & Powered by <strong class="text-amber-400 font-bold tracking-wide">mohamedtechpro</strong></span>
        </div>
        <p class="font-semibold text-slate-700">College of Business Education (CBE) &mdash; University Portal</p>
        <p class="mt-0.5">Student E-Logbook & GPS-Verified Field Attendance System &bull; &copy; {{ date('Y') }}</p>
        <p class="text-[11px] text-slate-400">
            System Designed & Developed with Proprietary Rights by <span class="font-bold text-blue-700">MOHAMEDY HAMADI MOHAMED</span> &bull; All Rights Reserved.
        </p>
    </footer>

    @stack('scripts')
</body>
</html>
