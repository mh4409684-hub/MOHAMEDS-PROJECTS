<x-layouts.cbe title="Supervisor Dashboard - CBE Portal">
    <!-- Header Banner -->
    <div class="mb-8 bg-gradient-to-r from-slate-900 via-emerald-950 to-teal-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                Field Supervisor Console
            </span>
            <h1 class="text-2xl md:text-3xl font-black mt-2">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-emerald-100/80 text-xs sm:text-sm mt-1">
                Oversee student field performance, verify GPS attendances, and review daily logbook submissions.
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('supervisor.logbooks.pending') }}" class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-lg transition">
                <i class="fa-solid fa-clipboard-check mr-1.5"></i> Review Logbooks
            </a>
            <a href="{{ route('supervisor.students.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/10 transition">
                <i class="fa-solid fa-users mr-1.5"></i> Assigned Students
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between text-xs font-semibold uppercase text-slate-500">
                <span>Assigned Students</span>
                <span class="p-2 rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid fa-users"></i></span>
            </div>
            <div class="mt-4 text-3xl font-black text-slate-900">{{ $stats['total_students'] ?? 0 }}</div>
            <div class="mt-2 text-xs text-slate-400">Students under your active supervision</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between text-xs font-semibold uppercase text-slate-500">
                <span>Pending Logbooks</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-clock-rotate-left"></i></span>
            </div>
            <div class="mt-4 text-3xl font-black text-slate-900">{{ $stats['pending_logbooks'] ?? 0 }}</div>
            <div class="mt-2 text-xs text-amber-600 font-semibold">Requires grading/approval</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between text-xs font-semibold uppercase text-slate-500">
                <span>Approved Entries</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600"><i class="fa-solid fa-circle-check"></i></span>
            </div>
            <div class="mt-4 text-3xl font-black text-slate-900">{{ $stats['approved_logbooks'] ?? 0 }}</div>
            <div class="mt-2 text-xs text-slate-400">Total activities validated</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between text-xs font-semibold uppercase text-slate-500">
                <span>Pending Reports</span>
                <span class="p-2 rounded-xl bg-purple-50 text-purple-600"><i class="fa-solid fa-file-lines"></i></span>
            </div>
            <div class="mt-4 text-3xl font-black text-slate-900">{{ $stats['pending_reports'] ?? 0 }}</div>
            <div class="mt-2 text-xs text-slate-400">Weekly training reports</div>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Logbooks for Quick Review -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900">Pending Daily Logbooks</h3>
                <a href="{{ route('supervisor.logbooks.pending') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">View All</a>
            </div>

            @if($pendingLogbooks->isEmpty())
                <p class="text-xs text-slate-400 py-8 text-center">No logbooks awaiting review right now.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($pendingLogbooks as $lb)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div>
                                <div class="text-xs font-bold text-slate-900">
                                    {{ $lb->fieldPlacement->student->user->name ?? 'Student' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    {{ $lb->activity_date?->format('M d, Y') }} &bull; {{ $lb->hours_worked }} hrs &bull; {{ Str::limit($lb->activity_description, 40) }}
                                </div>
                            </div>
                            <a href="{{ route('supervisor.logbooks.review', $lb->id) }}" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-xs transition">
                                Review
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Supervised Students -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900">Assigned Students</h3>
                <a href="{{ route('supervisor.students.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">View All</a>
            </div>

            @if($assignedPlacements->isEmpty())
                <p class="text-xs text-slate-400 py-8 text-center">No students currently assigned.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($assignedPlacements as $assignment)
                        @php $placement = $assignment->fieldPlacement; @endphp
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-700">
                                    {{ strtoupper(substr($placement->student->user->name ?? 'S', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-900">{{ $placement->student->user->name ?? 'Student' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $placement->hostOrganization->name ?? 'Host Org' }} &bull; {{ $placement->field_progress }}% completed</div>
                                </div>
                            </div>
                            <a href="{{ route('supervisor.students.show', $placement->id) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition">
                                Details
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
