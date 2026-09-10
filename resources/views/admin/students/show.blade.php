<x-layouts.cbe title="Student Profile - CBE Portal">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="h-14 w-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-xl">
                    {{ strtoupper(substr($student->user->name ?? 'S', 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900">{{ $student->user->name }}</h1>
                    <p class="text-xs text-slate-500 font-mono">{{ $student->user->registration_number }} &bull; {{ $student->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to Students
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Academic Information</h2>
                <div class="text-xs space-y-2">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Programme</span>
                        <span class="font-semibold text-slate-800">{{ $student->programme->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Campus</span>
                        <span class="font-semibold text-slate-800">{{ $student->campus->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Year of Study</span>
                        <span class="font-semibold text-slate-800">Year {{ $student->year_of_study }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-500">Enrollment Status</span>
                        <span class="font-bold text-emerald-600 uppercase">{{ $student->enrollment_status }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Field Placement</h2>
                @php $pl = $student->fieldPlacements()->where('status', 'active')->first(); @endphp
                @if($pl)
                    <div class="text-xs space-y-2">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Host Organization</span>
                            <span class="font-semibold text-slate-800">{{ $pl->hostOrganization->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Progress</span>
                            <span class="font-bold text-blue-600">{{ $pl->field_progress }}% ({{ $pl->days_completed }}/{{ $pl->total_days }} days)</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-500">GPS Geofence</span>
                            <span class="font-semibold text-emerald-600">{{ $pl->hostOrganization->geofence_radius_meters ?? 300 }}m radius</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 py-4">No active field placement assigned yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.cbe>
