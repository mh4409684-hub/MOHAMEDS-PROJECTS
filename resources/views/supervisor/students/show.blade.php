<x-layouts.cbe title="Supervisor - Student Placement Overview">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="h-12 w-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-lg">
                    {{ strtoupper(substr($placement->student->user->name ?? 'S', 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900">{{ $placement->student->user->name }}</h1>
                    <p class="text-xs text-slate-500">Placement at {{ $placement->hostOrganization->name }} &bull; {{ $placement->hostOrganization->city }}</p>
                </div>
            </div>
            <a href="{{ route('supervisor.students.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to Students
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Entries</span>
                <div class="text-2xl font-black text-slate-900 mt-2">{{ $logbookStats['total_entries'] ?? 0 }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ $logbookStats['approved_entries'] ?? 0 }} approved &bull; {{ $logbookStats['pending_entries'] ?? 0 }} pending</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Field Attendance</span>
                <div class="text-2xl font-black text-emerald-600 mt-2">{{ $attendanceStats['attendance_percentage'] ?? 0 }}%</div>
                <div class="text-xs text-slate-500 mt-1">{{ $attendanceStats['present_days'] ?? 0 }} days present</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Placement Progress</span>
                <div class="text-2xl font-black text-blue-600 mt-2">{{ $placement->field_progress }}%</div>
                <div class="text-xs text-slate-500 mt-1">{{ $placement->days_completed }} of {{ $placement->total_days }} days</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900">Student Logbook History</h3>
                <a href="{{ route('supervisor.logbooks.history', $placement->id) }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Full History</a>
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($placement->logbookEntries()->latest('activity_date')->take(5)->get() as $entry)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-900">{{ $entry->activity_date?->format('M d, Y') }}</span>
                            <span class="text-slate-500 ml-2">{{ Str::limit($entry->activity_description, 50) }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full uppercase font-bold text-[10px] {{ $entry->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($entry->status === 'submitted' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                            {{ $entry->status }}
                        </span>
                    </div>
                @empty
                    <p class="py-4 text-center text-slate-400">No logbook entries submitted yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.cbe>
