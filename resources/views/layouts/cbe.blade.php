<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CBE E-Logbook & Attendance System' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .bg-pattern {
            background-color: #0f172a;
            background-image: radial-gradient(rgba(59, 130, 246, 0.15) 1px, transparent 0);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased">
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

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-6">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p class="font-semibold text-slate-700">College of Business Education (CBE) &mdash; University Portal</p>
        <p class="mt-1">Student E-Logbook & GPS-Verified Field Attendance System &bull; &copy; {{ date('Y') }}</p>
    </footer>

    @stack('scripts')
</body>
</html>
