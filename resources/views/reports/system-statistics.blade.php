<x-layouts.cbe title="Takwimu za Mfumo - CBE">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
            <div>
                <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline mb-1 inline-block">
                    &larr; Rudi Kituo cha Ripoti
                </a>
                <h1 class="text-2xl font-black text-slate-900">{{ $report['title'] ?? 'System Statistics Report' }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Imezalishwa: {{ $report['generated_at'] ?? now() }}</p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Chapisha / Print PDF
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Students -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase">Wanafunzi (Students)</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900">{{ $report['students']['total'] ?? 0 }}</p>
                <div class="space-y-1 text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <div class="flex justify-between"><span>Active:</span> <span class="font-bold text-emerald-600">{{ $report['students']['active'] ?? 0 }}</span></div>
                    <div class="flex justify-between"><span>Graduated:</span> <span class="font-bold text-blue-600">{{ $report['students']['graduated'] ?? 0 }}</span></div>
                    <div class="flex justify-between"><span>Withdrawn:</span> <span class="font-bold text-slate-600">{{ $report['students']['withdrawn'] ?? 0 }}</span></div>
                </div>
            </div>

            <!-- Staff -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase">Wafanyakazi & Wasimamizi</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900">{{ $report['staff']['total'] ?? 0 }}</p>
                <div class="space-y-1 text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <div class="flex justify-between"><span>Lecturers:</span> <span class="font-bold text-slate-800">{{ $report['staff']['lecturers'] ?? 0 }}</span></div>
                    <div class="flex justify-between"><span>Field Supervisors:</span> <span class="font-bold text-emerald-600">{{ $report['staff']['supervisors'] ?? 0 }}</span></div>
                    <div class="flex justify-between"><span>Coordinators:</span> <span class="font-bold text-blue-600">{{ $report['staff']['coordinators'] ?? 0 }}</span></div>
                </div>
            </div>

            <!-- Field Placements -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase">Nafasi za Field</span>
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900">{{ $report['field_placements']['total'] ?? 0 }}</p>
                <div class="space-y-1 text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <div class="flex justify-between"><span>Zinazoendelea (Active):</span> <span class="font-bold text-emerald-600">{{ $report['field_placements']['active'] ?? 0 }}</span></div>
                    <div class="flex justify-between"><span>Zilizokamilika:</span> <span class="font-bold text-blue-600">{{ $report['field_placements']['completed'] ?? 0 }}</span></div>
                    <div class="flex justify-between"><span>Zilizosimama:</span> <span class="font-bold text-rose-600">{{ $report['field_placements']['suspended'] ?? 0 }}</span></div>
                </div>
            </div>

            <!-- Logbooks -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase">Kumbukumbu za Logbook</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-book"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900">{{ $report['logbooks']['total'] ?? 0 }}</p>
                <div class="space-y-1 text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <div class="flex justify-between"><span>Zilizoidhinishwa:</span> <span class="font-bold text-emerald-600">{{ $report['logbooks']['approved'] ?? 0 }}</span></div>
                    <div class="flex justify-between"><span>Zinazosubiri:</span> <span class="font-bold text-amber-600">{{ $report['logbooks']['submitted'] ?? 0 }}</span></div>
                    <div class="flex justify-between"><span>Zilizokataliwa:</span> <span class="font-bold text-rose-600">{{ $report['logbooks']['rejected'] ?? 0 }}</span></div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.cbe>
