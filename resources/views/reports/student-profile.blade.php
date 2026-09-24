<x-layouts.cbe title="Ripoti ya Wasifu wa Mwanafunzi - CBE">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
            <div>
                <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline mb-1 inline-block">
                    &larr; Rudi Kituo cha Ripoti
                </a>
                <h1 class="text-2xl font-black text-slate-900">{{ $report['title'] ?? 'Student Profile Report' }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Imezalishwa: {{ $report['generated_at'] ?? now() }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ request()->fullUrlWithQuery(['format' => 'excel']) }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i> Pakua Excel (CSV)
                </a>
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow transition flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Chapisha / Print PDF
                </button>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6 print:border-none print:shadow-none print:p-0">
            <!-- Student Header -->
            <div class="border-b border-slate-100 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">{{ $report['student']['name'] ?? 'Mwanafunzi' }}</h2>
                        <p class="text-xs font-mono text-slate-500 mt-1">
                            Reg No: <span class="font-bold text-slate-700">{{ $report['student']['registration_number'] ?? '-' }}</span> &bull; 
                            Email: <span class="font-bold text-slate-700">{{ $report['student']['email'] ?? '-' }}</span>
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                        {{ $report['student']['enrollment_status'] ?? 'Enrolled' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px]">PROGRAMME</span>
                        <span class="font-bold text-slate-800">{{ $report['student']['programme'] ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">CAMPUS</span>
                        <span class="font-bold text-slate-800">{{ $report['student']['campus'] ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">SECTION</span>
                        <span class="font-bold text-slate-800">{{ $report['student']['section'] ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">YEAR OF STUDY</span>
                        <span class="font-bold text-slate-800">Year {{ $report['student']['year_of_study'] ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Field Placement Info -->
            @if(!empty($report['field_information']))
                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-blue-600"></i>
                        Taarifa za Mafunzo kwa Vitendo (Field Placement)
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl text-xs border border-slate-100">
                        <div>
                            <span class="text-slate-400 block text-[10px]">SHIRIKA (ORGANIZATION)</span>
                            <span class="font-bold text-slate-800">{{ $report['field_information']['host_organization'] ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">MAENEO</span>
                            <span class="font-bold text-slate-800">{{ $report['field_information']['organization_address'] ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">KIPINDI</span>
                            <span class="font-bold text-slate-800 font-mono">{{ $report['field_information']['start_date'] ?? '-' }} &ndash; {{ $report['field_information']['end_date'] ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">MAENDELEO (PROGRESS)</span>
                            <span class="font-bold text-blue-600">{{ $report['field_information']['field_progress'] ?? '0%' }} ({{ $report['field_information']['days_completed'] ?? 0 }}/{{ $report['field_information']['total_days'] ?? 0 }} siku)</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Attendance & Logbook Stats -->
            @if(!empty($report['field_attendance']) || !empty($report['logbook_summary']))
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if(!empty($report['field_attendance']))
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 space-y-2">
                            <h4 class="text-xs font-bold text-slate-700 uppercase">Muhtasari wa Mahudhurio (Field Attendance)</h4>
                            <div class="grid grid-cols-3 gap-2 text-center pt-2">
                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block">Alihudhuria</span>
                                    <span class="text-lg font-bold text-emerald-600">{{ $report['field_attendance']['present_days'] ?? 0 }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block">Alichelewa</span>
                                    <span class="text-lg font-bold text-amber-600">{{ $report['field_attendance']['late_days'] ?? 0 }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block">Hakufika</span>
                                    <span class="text-lg font-bold text-rose-600">{{ $report['field_attendance']['absent_days'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($report['logbook_summary']))
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 space-y-2">
                            <h4 class="text-xs font-bold text-slate-700 uppercase">Muhtasari wa Logbook (E-Logbook)</h4>
                            <div class="grid grid-cols-4 gap-2 text-center pt-2">
                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block">Zilizoingizwa</span>
                                    <span class="text-base font-bold text-slate-800">{{ $report['logbook_summary']['total_entries'] ?? 0 }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block">Zilizokubaliwa</span>
                                    <span class="text-base font-bold text-emerald-600">{{ $report['logbook_summary']['approved'] ?? 0 }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block">Zinazosubiri</span>
                                    <span class="text-base font-bold text-amber-600">{{ $report['logbook_summary']['pending'] ?? 0 }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block">Masaa</span>
                                    <span class="text-base font-bold text-blue-600">{{ $report['logbook_summary']['total_hours'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
