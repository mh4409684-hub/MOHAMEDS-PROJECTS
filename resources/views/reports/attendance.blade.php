<x-layouts.cbe title="Ripoti ya Mahudhurio ya Darasani - CBE">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
            <div>
                <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline mb-1 inline-block">
                    &larr; Rudi Kituo cha Ripoti
                </a>
                <h1 class="text-2xl font-black text-slate-900">{{ $report['title'] ?? 'Attendance Report' }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Somo: <span class="font-bold text-slate-700">{{ $report['course'] ?? 'N/A' }}</span> &bull; 
                    Section: <span class="font-bold text-slate-700">{{ $report['section'] ?? 'N/A' }}</span> &bull; 
                    Kipindi: <span class="font-bold text-slate-700">{{ $report['period'] ?? 'All time' }}</span>
                </p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Chapisha / Print PDF
            </button>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6 print:border-none print:shadow-none print:p-0">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <span class="text-xs font-bold text-slate-400 uppercase">Jumla ya Vipindi (Total Sessions): {{ $report['total_sessions'] ?? 0 }}</span>
                <span class="text-xs text-slate-400 font-mono">Imezalishwa: {{ $report['generated_at'] ?? now() }}</span>
            </div>

            @if(empty($report['student_attendance']))
                <div class="py-12 text-center text-slate-500 text-xs">
                    Hakuna rekodi za mahudhurio zilizopatikana kwa kigezo hiki.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Jina la Mwanafunzi</th>
                                <th class="px-4 py-3">Registration No</th>
                                <th class="px-4 py-3 text-center">Vipindi Vilivyohudhuriwa</th>
                                <th class="px-4 py-3 text-center">Asilimia (%)</th>
                                <th class="px-4 py-3">Hadhi ya Mtihani</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($report['student_attendance'] as $index => $row)
                                <tr>
                                    <td class="px-4 py-3 text-slate-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-900">{{ $row['name'] }}</td>
                                    <td class="px-4 py-3 font-mono">{{ $row['registration_number'] }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-800">
                                        {{ $row['total_present'] }} / {{ $row['total_sessions'] }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $row['percentage'] >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ $row['percentage'] }}%
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($row['percentage'] >= 75)
                                            <span class="text-emerald-700 font-bold text-[11px]"><i class="fa-solid fa-check mr-1"></i> Anaruhusiwa</span>
                                        @else
                                            <span class="text-rose-700 font-bold text-[11px]"><i class="fa-solid fa-xmark mr-1"></i> Chini ya 75%</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
