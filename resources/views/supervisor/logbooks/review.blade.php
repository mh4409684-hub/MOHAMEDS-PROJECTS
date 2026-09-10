<x-layouts.cbe title="Review Logbook Entry - CBE Portal">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400">Supervisor Review</span>
                <h1 class="text-xl font-black text-slate-900 mt-0.5">
                    Entry by {{ $entry->fieldPlacement->student->user->name ?? 'Student' }}
                </h1>
                <p class="text-xs text-slate-500">
                    Activity Date: {{ $entry->activity_date?->format('l, F d, Y') }} &bull; {{ $entry->hours_worked }} Hours
                </p>
            </div>
            <a href="{{ route('supervisor.dashboard') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            <div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Student Tasks & Duties</h3>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line leading-relaxed">
                    {{ $entry->activity_description }}
                </div>
            </div>

            @if($entry->skills_learned)
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Skills & Knowledge Acquired</h3>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line">
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
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Solutions</h3>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line">
                                {{ $entry->solutions }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Review Actions Form -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h3 class="text-sm font-bold text-slate-900">Supervisor Decision & Feedback</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Approve Form -->
                    <form method="POST" action="{{ route('supervisor.logbooks.approve', $entry->id) }}" class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-100 space-y-3">
                        @csrf
                        <label class="block text-xs font-bold text-emerald-900">Approve Entry with Remarks</label>
                        <textarea name="feedback" rows="2" placeholder="e.g. Well documented, good demonstration of system troubleshooting..." class="w-full px-3 py-2 text-xs rounded-lg border border-emerald-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        <button type="submit" class="w-full py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition">
                            <i class="fa-solid fa-check mr-1.5"></i> Approve Logbook Entry
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <form method="POST" action="{{ route('supervisor.logbooks.reject', $entry->id) }}" class="p-4 rounded-xl bg-rose-50/60 border border-rose-100 space-y-3">
                        @csrf
                        <label class="block text-xs font-bold text-rose-900">Request Revisions / Reject</label>
                        <textarea name="rejection_reason" rows="2" required placeholder="State specifically what needs to be expanded or corrected..." class="w-full px-3 py-2 text-xs rounded-lg border border-rose-200 bg-white focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                        <button type="submit" class="w-full py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow transition">
                            <i class="fa-solid fa-rotate-left mr-1.5"></i> Request Revision
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.cbe>
