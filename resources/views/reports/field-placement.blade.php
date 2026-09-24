<x-layouts.cbe title="Ripoti ya Field Placement - CBE">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
            <div>
                <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline mb-1 inline-block">
                    &larr; Rudi Kituo cha Ripoti
                </a>
                <h1 class="text-2xl font-black text-slate-900">{{ $report['title'] ?? 'Field Placement Report' }}</h1>
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
            <!-- Student & Host Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Taarifa za Mwanafunzi</span>
                    <p class="text-base font-black text-slate-900">{{ $report['student_information']['name'] ?? 'N/A' }}</p>
                    <p class="text-slate-600">Reg No: <span class="font-bold text-slate-800">{{ $report['student_information']['registration_number'] ?? '-' }}</span></p>
                    <p class="text-slate-600">Programme: <span class="font-bold text-slate-800">{{ $report['student_information']['programme'] ?? '-' }}</span></p>
                    <p class="text-slate-600">Email: <span class="font-bold text-slate-800">{{ $report['student_information']['email'] ?? '-' }}</span></p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Shirika na Msimamizi</span>
                    <p class="text-base font-black text-slate-900">{{ $report['placement_information']['host_organization'] ?? 'N/A' }}</p>
                    <p class="text-slate-600">Anwani: <span class="font-bold text-slate-800">{{ $report['placement_information']['address'] ?? '-' }}</span></p>
                    <p class="text-slate-600">Msimamizi wa Chuo: <span class="font-bold text-slate-800">{{ $report['supervisor_information']['name'] ?? 'Hajapangiwa' }}</span></p>
                    <p class="text-slate-600">Kipindi: <span class="font-bold text-slate-800 font-mono">{{ $report['placement_information']['start_date'] ?? '-' }} &ndash; {{ $report['placement_information']['end_date'] ?? '-' }}</span></p>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100">
                    <span class="text-[10px] font-bold text-blue-700 uppercase">Maendeleo (Progress)</span>
                    <p class="text-2xl font-black text-blue-800 mt-1">{{ $report['placement_information']['progress'] ?? 0 }}%</p>
                    <span class="text-[10px] text-blue-600 font-medium">{{ $report['placement_information']['days_completed'] ?? 0 }} / {{ $report['placement_information']['total_days'] ?? 0 }} siku</span>
                </div>

                <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-100">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase">Mahudhurio (Attendance)</span>
                    <p class="text-2xl font-black text-emerald-800 mt-1">{{ $report['attendance_summary']['percentage'] ?? 0 }}%</p>
                    <span class="text-[10px] text-emerald-600 font-medium">{{ $report['attendance_summary']['present'] ?? 0 }} siku zilizofika</span>
                </div>

                <div class="p-4 rounded-xl bg-purple-50/60 border border-purple-100">
                    <span class="text-[10px] font-bold text-purple-700 uppercase">Logbook Entries</span>
                    <p class="text-2xl font-black text-purple-800 mt-1">{{ $report['logbook_summary']['total_entries'] ?? 0 }}</p>
                    <span class="text-[10px] text-purple-600 font-medium">{{ $report['logbook_summary']['approved'] ?? 0 }} zilizokubaliwa</span>
                </div>

                <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-100">
                    <span class="text-[10px] font-bold text-amber-700 uppercase">Jumla ya Masaa</span>
                    <p class="text-2xl font-black text-amber-800 mt-1">{{ $report['logbook_summary']['total_hours'] ?? 0 }} hrs</p>
                    <span class="text-[10px] text-amber-600 font-medium">Masaa ya kazi za field</span>
                </div>
            </div>

            <!-- Logbook Entries List -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 mb-3">Kumbukumbu za Logbook za Mwanafunzi</h3>
                @if(empty($report['logbook_entries']))
                    <p class="text-xs text-slate-400 italic">Hakuna kumbukumbu za logbook kwa mwanafunzi huyu.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                                <tr>
                                    <th class="px-3 py-2">Tarehe</th>
                                    <th class="px-3 py-2">Kazi Zilizofanyika</th>
                                    <th class="px-3 py-2">Masaa</th>
                                    <th class="px-3 py-2">Hali</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($report['logbook_entries'] as $en)
                                    <tr>
                                        <td class="px-3 py-2.5 font-mono font-bold whitespace-nowrap">{{ $en['date'] }}</td>
                                        <td class="px-3 py-2.5 text-slate-800">{{ $en['activity'] }}</td>
                                        <td class="px-3 py-2.5 font-mono font-bold">{{ $en['hours'] }}h</td>
                                        <td class="px-3 py-2.5">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $en['status'] === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-700' }}">
                                                {{ ucfirst($en['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.cbe>
