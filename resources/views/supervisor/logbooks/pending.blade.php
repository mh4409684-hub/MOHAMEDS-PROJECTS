<x-layouts.cbe title="Pending Logbooks - CBE Portal">
    <div class="space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Pending Daily Logbooks</h1>
                <p class="text-xs text-slate-500 mt-1">Review student practical submissions waiting for your verification.</p>
            </div>
            <a href="{{ route('supervisor.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($logbooks->isEmpty())
                <p class="text-xs text-slate-400 py-10 text-center">No logbooks awaiting review.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($logbooks as $lb)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="text-sm font-bold text-slate-900">
                                    {{ $lb->fieldPlacement->student->user->name ?? 'Student' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $lb->activity_date?->format('l, M d, Y') }} &bull; {{ $lb->hours_worked }} Hours
                                </div>
                                <p class="text-xs text-slate-600 mt-1 max-w-xl truncate">{{ $lb->activity_description }}</p>
                            </div>
                            <a href="{{ route('supervisor.logbooks.review', $lb->id) }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shrink-0">
                                Review & Grade
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
