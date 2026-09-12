<x-layouts.cbe title="Kagua Ripoti ya Wiki - CBE Supervisor">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400">Ukaguzi wa Msimamizi</span>
                <h1 class="text-xl font-black text-slate-900 mt-0.5">
                    Ripoti ya Wiki ya {{ $report->week_number }} - {{ $report->fieldPlacement->student->user->name ?? 'Mwanafunzi' }}
                </h1>
                <p class="text-xs text-slate-500">
                    Kipindi cha Wiki: {{ $report->week_start_date?->format('d M, Y') ?? '-' }} &ndash; {{ $report->week_end_date?->format('d M, Y') ?? '-' }}
                </p>
            </div>
            <a href="{{ route('supervisor.reports.pending') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                &larr; Rudi Nyuma
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            <div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Muhtasari wa Majukumu / Kazi Zilizofanyika (Activities Completed)</h3>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line leading-relaxed">
                    {{ $report->activities_completed ?? $report->summary ?? 'Hakuna maelezo yaliyowekwa.' }}
                </div>
            </div>

            @if($report->skills_acquired)
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ujuzi na Uzoefu Uliopatikana (Skills Acquired)</h3>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line">
                        {{ $report->skills_acquired }}
                    </div>
                </div>
            @endif

            @if($report->challenges || $report->solutions)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($report->challenges)
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Changamoto (Challenges)</h3>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line">
                                {{ $report->challenges }}
                            </div>
                        </div>
                    @endif
                    @if($report->solutions)
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Masuluhisho (Solutions)</h3>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-sm text-slate-800 whitespace-pre-line">
                                {{ $report->solutions }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Review Actions Form -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h3 class="text-sm font-bold text-slate-900">Uamuzi na Maoni ya Msimamizi (Supervisor Decision & Remarks)</h3>

                <div class="grid grid-cols-1 gap-4">
                    <!-- Approve Form -->
                    <form method="POST" action="{{ route('supervisor.reports.approve', $report->id) }}" class="p-5 rounded-xl bg-emerald-50/60 border border-emerald-100 space-y-3">
                        @csrf
                        <label class="block text-xs font-bold text-emerald-900">
                            Thibitisha na Weka Maoni (Approve Report)
                        </label>
                        <textarea name="comments" rows="3" placeholder="Mfano: Ripoti imeandikwa vizuri, kazi zinaendana na malengo ya mafunzo..." class="w-full px-3 py-2 text-xs rounded-lg border border-emerald-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        <button type="submit" class="w-full py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition">
                            <i class="fa-solid fa-check-circle mr-1.5"></i> Thibitisha Ripoti Hii (Approve Weekly Report)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.cbe>
