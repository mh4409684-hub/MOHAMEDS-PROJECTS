<x-layouts.cbe title="Supervisor - Assigned Students">
    <div class="space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">My Supervised Students</h1>
                <p class="text-xs text-slate-500 mt-1">Field placements assigned to you for ongoing academic evaluation.</p>
            </div>
            <a href="{{ route('supervisor.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($assignments->isEmpty())
                <p class="text-xs text-slate-400 py-8 text-center">No students currently assigned.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($assignments as $assignment)
                        @php $placement = $assignment->fieldPlacement; @endphp
                        <div class="py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($placement->student->user->name ?? 'S', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $placement->student->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $placement->hostOrganization->name }} &bull; {{ $placement->hostOrganization->city }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs font-semibold text-slate-600">{{ $placement->field_progress }}% completed</span>
                                <a href="{{ route('supervisor.students.show', $placement->id) }}" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs hover:bg-emerald-100 transition">
                                    View Student
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
