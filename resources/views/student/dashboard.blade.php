<x-layouts.cbe title="Student Dashboard - CBE Portal">
    <!-- Header Banner -->
    <div class="mb-8 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30 text-xs font-semibold mb-3">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Student Workspace &bull; {{ $dashboardData['student']->user->registration_number ?? 'REG-001' }}</span>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight">
                    Hello, {{ auth()->user()->name }}!
                </h1>
                <p class="text-blue-100/80 text-sm mt-1 max-w-2xl">
                    @if($fieldPlacement)
                        Field Placement at <span class="text-white font-semibold underline decoration-blue-400">{{ $fieldPlacement->hostOrganization->name ?? 'Host Organization' }}</span> ({{ $fieldPlacement->hostOrganization->city ?? 'Tanzania' }}). Track your daily logbook and mark GPS attendance below.
                    @else
                        Welcome to your University E-Logbook & Attendance workspace.
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('student.field-attendance') }}" class="inline-flex items-center px-5 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm shadow-lg shadow-emerald-500/30 transition transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-location-crosshairs text-base mr-2"></i>
                    GPS Check-In
                </a>
                <a href="{{ route('student.elogbook.create') }}" class="inline-flex items-center px-5 py-3 rounded-xl bg-white hover:bg-blue-50 text-slate-900 font-bold text-sm shadow-lg transition transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-pen-to-square text-base mr-2 text-blue-600"></i>
                    New Log Entry
                </a>
            </div>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Placement Progress -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Field Progress</span>
                <span class="p-2 rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid fa-chart-line text-lg"></i></span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-black text-slate-900">{{ $dashboardData['field_progress'] ?? 0 }}%</span>
                <span class="text-xs font-medium text-slate-500">{{ $dashboardData['days_remaining'] ?? 0 }} days left</span>
            </div>
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $dashboardData['field_progress'] ?? 0) }}%"></div>
            </div>
        </div>

        <!-- Field Attendance % -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Field Attendance</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600"><i class="fa-solid fa-location-dot text-lg"></i></span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-black text-slate-900">{{ $dashboardData['attendance_percentage'] ?? 0 }}%</span>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">GPS verified</span>
            </div>
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, $dashboardData['attendance_percentage'] ?? 0) }}%"></div>
            </div>
        </div>

        <!-- Approved Log Entries -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Approved Logbooks</span>
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600"><i class="fa-solid fa-clipboard-check text-lg"></i></span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-black text-slate-900">{{ $dashboardData['approved_activities'] ?? 0 }}</span>
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">{{ $dashboardData['pending_activities'] ?? 0 }} pending</span>
            </div>
            <div class="mt-3 text-xs text-slate-400">Reviewed by assigned field supervisor</div>
        </div>

        <!-- Class Attendance -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Class Attendance</span>
                <span class="p-2 rounded-xl bg-purple-50 text-purple-600"><i class="fa-solid fa-qrcode text-lg"></i></span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-black text-slate-900">{{ $classAttendancePercentage ?? 0 }}%</span>
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">Lecture Sessions</span>
            </div>
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(100, $classAttendancePercentage ?? 0) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Quick Action Cards & Placement Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Placement & Organization Details -->
        <div class="lg:col-span-2 space-y-6">
            @if($fieldPlacement)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3">
                            <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $fieldPlacement->hostOrganization->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $fieldPlacement->hostOrganization->industry ?? 'Industry Training' }} &bull; {{ $fieldPlacement->hostOrganization->city }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                            Active Placement
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-400 block mb-1">Supervisor</span>
                            <span class="text-sm font-semibold text-slate-800">
                                {{ $fieldPlacement->supervisorAssignments->first()?->supervisor?->user?->name ?? 'Assigned by Faculty' }}
                            </span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-400 block mb-1">Duration</span>
                            <span class="text-sm font-semibold text-slate-800">
                                {{ $fieldPlacement->start_date?->format('M d') }} - {{ $fieldPlacement->end_date?->format('M d, Y') }}
                            </span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-400 block mb-1">Days Logged</span>
                            <span class="text-sm font-semibold text-slate-800">
                                {{ $fieldPlacement->days_completed }} / {{ $fieldPlacement->total_days }} days
                            </span>
                        </div>
                    </div>

                    <!-- Geofencing status -->
                    <div class="mt-6 p-4 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-shield-halved text-blue-600 text-xl"></i>
                            <div>
                                <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider">GPS Geofencing Activated</h4>
                                <p class="text-xs text-blue-700">Check-in requires you to be within {{ $fieldPlacement->hostOrganization->geofence_radius_meters ?? 300 }}m of placement coordinates.</p>
                            </div>
                        </div>
                        <a href="{{ route('student.field-attendance') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition">
                            Open GPS Check-in
                        </a>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500">Unahitaji kurekebisha taarifa za eneo au msimamizi wako?</span>
                        <a href="{{ route('student.field-placement.apply') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                            <i class="fa-solid fa-pen-to-square"></i> Badili Taarifa za Field
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-blue-200/80 shadow-sm p-6 sm:p-8 text-center space-y-4">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto text-2xl shadow-inner">
                        <i class="fa-solid fa-building-circle-check"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Umeshapata Eneo la Field?</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                            Kama umeshapata kampuni, shirika, benki, au ofisi ya kufanyia mafunzo ya vitendo, bonyeza kitufe hapa chini ujaze taarifa za taasisi na namba ya simu ya msimamizi wako kazini.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('student.field-placement.apply') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-xs shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-plus-circle text-sm"></i> Jaza Eneo la Field Ulilopata Sasa Hivi &rarr;
                        </a>
                    </div>
                    <p class="text-[11px] text-slate-400 italic">
                        (Kumbuka: Admin pia anaweza kukupangia au kukusaidia kukamilisha eneo la mafunzo wakati wowote).
                    </p>
                </div>
            @endif

            <!-- Quick Navigation Tiles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('student.elogbook.index') }}" class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-blue-400 hover:shadow-md transition group">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fa-solid fa-book-open text-lg"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">E-Logbook Entries</h4>
                    <p class="text-xs text-slate-500 mt-1">Write daily entries, skills acquired, and challenges faced during your training.</p>
                </a>

                <a href="{{ route('student.weekly-reports') }}" class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-400 hover:shadow-md transition group">
                    <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <i class="fa-solid fa-calendar-check text-lg"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Weekly Summaries</h4>
                    <p class="text-xs text-slate-500 mt-1">Compile and submit weekly progress reports for formal grading.</p>
                </a>
            </div>
        </div>

        <!-- Right Column: Notifications & Class Attendance Code -->
        <div class="space-y-6">
            <!-- Mark Lecture Class Attendance -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="h-9 w-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-key text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Lecture Attendance Code</h3>
                        <p class="text-xs text-slate-500">Enter code provided by lecturer</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('student.mark-attendance') }}" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" name="attendance_code" placeholder="e.g. 783921" required class="w-full px-4 py-2.5 text-center tracking-widest text-lg font-mono font-bold uppercase rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                        Submit Code
                    </button>
                </form>
            </div>

            <!-- Notifications -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-900">Notifications</h3>
                    <span class="px-2 py-0.5 text-[11px] rounded-full bg-blue-100 text-blue-700 font-semibold">{{ $notifications->count() }} new</span>
                </div>

                @if($notifications->isEmpty())
                    <p class="text-xs text-slate-400 py-4 text-center">No new notifications.</p>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($notifications as $notif)
                            <div class="py-3">
                                <div class="text-xs font-bold text-slate-900">{{ $notif->title }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $notif->message }}</div>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.cbe>
