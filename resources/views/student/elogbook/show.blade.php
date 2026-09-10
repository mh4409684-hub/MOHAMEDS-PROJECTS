<x-layouts.cbe title="Logbook Entry Details - CBE Portal">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400">Logbook Entry</span>
                <h1 class="text-xl font-black text-slate-900 mt-0.5">
                    {{ $entry->activity_date ? $entry->activity_date->format('l, F d, Y') : 'Date N/A' }}
                </h1>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('student.elogbook.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Back to List
                </a>
                @if($entry->status === 'draft')
                    <form method="POST" action="{{ route('student.elogbook.submit', $entry->id) }}">
                        @csrf
                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow">
                            Submit for Approval
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wider block">Hours Worked</span>
                    <span class="text-lg font-bold text-slate-900">{{ $entry->hours_worked }} Hours</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wider block text-right">Review Status</span>
                    @if($entry->status === 'approved')
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase">Approved</span>
                    @elseif($entry->status === 'submitted')
                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase">Submitted &bull; Pending Review</span>
                    @elseif($entry->status === 'rejected')
                        <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold uppercase">Revision Requested</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold uppercase">Draft</span>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Activity Description</h3>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line leading-relaxed">
                    {{ $entry->activity_description }}
                </div>
            </div>

            @if($entry->skills_learned)
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Skills & Knowledge Acquired</h3>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line leading-relaxed">
                        {{ $entry->skills_learned }}
                    </div>
                </div>
            @endif

            @if($entry->challenges || $entry->solutions)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($entry->challenges)
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Challenges</h3>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line">
                                {{ $entry->challenges }}
                            </div>
                        </div>
                    @endif
                    @if($entry->solutions)
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Solutions Applied</h3>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line">
                                {{ $entry->solutions }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if($entry->supervisor_feedback)
                <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                    <h3 class="text-xs font-bold text-indigo-900 uppercase tracking-wider mb-1">Supervisor Remarks</h3>
                    <p class="text-sm text-indigo-800">{{ $entry->supervisor_feedback }}</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
