<x-layouts.cbe title="Admin Dashboard - CBE Portal">
    <!-- Welcome Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 p-6 rounded-2xl shadow-xl text-white">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-400/30">
                    Administrator Workspace
                </span>
                <span class="text-xs text-slate-300">CBE Management Console</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-2 text-white">
                Welcome back, {{ auth()->user()->name }}
            </h1>
            <p class="text-slate-300 text-sm mt-1">
                Monitor field placements, student logbooks, GPS attendances, and academic activities across campuses.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg transition">
                <i class="fa-solid fa-user-plus mr-2"></i> Register Student
            </a>
            <a href="{{ route('admin.field-placements') }}" class="inline-flex items-center px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-briefcase mr-2"></i> Placements
            </a>
        </div>
    </div>

    @if(isset($pendingStudentRegistrations) && $pendingStudentRegistrations->isNotEmpty())
        <div class="mb-8 p-5 bg-gradient-to-r from-amber-500/15 via-amber-500/5 to-white border border-amber-200 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 text-xl shadow-md shadow-amber-500/30">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        Wanafunzi {{ $pendingStudentRegistrations->count() }} Wanasubiri Kuidhinishwa (Pending Approval)
                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold uppercase tracking-wide">Mpya</span>
                    </h3>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Wanafunzi wamejisajili kwenye tovuti na wanahitaji uthibitisho wako ili waweze kuingia kwenye akaunti zao.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.students.pending') }}" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check-double"></i> Kagua & Idhinisha Sasa
                </a>
            </div>
        </div>
    @endif

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Students Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Enrolled</span>
                <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid fa-user-graduate text-lg"></i></span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_students'] ?? 0 }}</span>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                    {{ $stats['active_students'] ?? 0 }} Active
                </span>
            </div>
            <div class="mt-3 text-xs text-slate-400">Students currently registered in system</div>
        </div>

        <!-- Field Placements -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Field Placements</span>
                <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600"><i class="fa-solid fa-location-dot text-lg"></i></span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_field_students'] ?? 0 }}</span>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">In Field</span>
            </div>
            <div class="mt-3 text-xs text-slate-400">GPS attendance tracked daily</div>
        </div>

        <!-- Pending Logbooks -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pending Logbooks</span>
                <span class="p-2.5 rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-clock-rotate-left text-lg"></i></span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['pending_logbooks'] ?? 0 }}</span>
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Review needed</span>
            </div>
            <div class="mt-3 text-xs text-slate-400">Awaiting supervisor sign-off</div>
        </div>

        <!-- Campuses & Staff -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Campuses & Staff</span>
                <span class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600"><i class="fa-solid fa-building-columns text-lg"></i></span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_campuses'] ?? 0 }}</span>
                <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                    {{ $stats['total_staff'] ?? 0 }} Staff
                </span>
            </div>
            <div class="mt-3 text-xs text-slate-400">{{ $stats['total_programmes'] ?? 0 }} Accredited programmes</div>
        </div>
    </div>

    <!-- Main 2-Column Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Recent Logbook Approvals & Quick Student List -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Pending Logbook Entries -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Recent Submitted Logbooks</h2>
                        <p class="text-xs text-slate-500">Student daily training entries requiring review</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg">
                        {{ $pendingApprovals->count() }} Entries
                    </span>
                </div>

                @if($pendingApprovals->isEmpty())
                    <div class="py-8 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <i class="fa-regular fa-clipboard text-3xl mb-2"></i>
                        <p class="text-sm font-medium">No pending logbook entries right now.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-semibold border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3">Student</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Activity Preview</th>
                                    <th class="px-4 py-3">Hours</th>
                                    <th class="px-4 py-3 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($pendingApprovals as $approval)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-3 font-medium text-slate-900">
                                            {{ $approval->fieldPlacement->student->user->name ?? 'Student' }}
                                            <span class="block text-xs text-slate-400">{{ $approval->fieldPlacement->student->user->registration_number ?? '' }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-500">
                                            {{ $approval->activity_date ? $approval->activity_date->format('M d, Y') : '' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs max-w-xs truncate text-slate-600">
                                            {{ Str::limit($approval->activity_description, 45) }}
                                        </td>
                                        <td class="px-4 py-3 text-xs font-semibold">{{ $approval->hours_worked }} hrs</td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                                Submitted
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Recent Students -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Recent Students</h2>
                        <p class="text-xs text-slate-500">Recently enrolled students in the portal</p>
                    </div>
                    <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        View All <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($recentStudents->isEmpty())
                    <div class="py-8 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <i class="fa-solid fa-user-graduate text-3xl mb-2"></i>
                        <p class="text-sm font-medium">No students enrolled yet.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($recentStudents as $student)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-9 w-9 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($student->user->name ?? 'S', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">{{ $student->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-400">{{ $student->user->email ?? '' }} &bull; {{ $student->programme->name ?? 'BIT' }}</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ ucfirst($student->enrollment_status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right 1 Col: Quick Links & System Status -->
        <div class="space-y-8">
            <!-- Shortcuts Card -->
            <div class="bg-gradient-to-br from-indigo-900 to-blue-900 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-bold mb-2">GPS Verification Mode</h3>
                <p class="text-xs text-blue-200 mb-6 leading-relaxed">
                    Students checking in to their field organization must have their GPS location verified within the organization's preset radius.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('admin.field-placements') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-map-location-dot text-emerald-400"></i>
                            <span>Placement Locations</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-blue-200"></i>
                    </a>
                    <a href="{{ route('admin.academic-settings') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-sliders text-amber-300"></i>
                            <span>Academic Settings</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-blue-200"></i>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-file-export text-sky-300"></i>
                            <span>Audit & Compliance</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-blue-200"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Staff Members -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900">Academic & Field Staff</h3>
                    <a href="{{ route('admin.staff.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">All Staff</a>
                </div>
                @if($recentStaff->isEmpty())
                    <p class="text-xs text-slate-400 py-4 text-center">No staff members listed yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentStaff as $st)
                            <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">{{ $st->user->name ?? 'Staff' }}</div>
                                        <div class="text-[11px] text-slate-400 capitalize">{{ $st->staff_type }} &bull; {{ $st->designation ?? 'Supervisor' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.cbe>
