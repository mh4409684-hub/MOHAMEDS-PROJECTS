<x-layouts.cbe title="Mahudhurio ya Field - CBE Supervisor">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <span class="text-xs font-semibold text-slate-400">Ukaguzi wa Mahudhurio (GPS Field Attendance)</span>
                <h1 class="text-2xl font-black text-slate-900 mt-0.5">
                    Mahudhurio ya {{ $placement->student->user->name ?? 'Mwanafunzi' }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Shirika: <span class="font-bold text-slate-700">{{ $placement->hostOrganization->name ?? 'N/A' }}</span> &bull; 
                    Reg: <span class="font-mono font-bold text-slate-700">{{ $placement->student->user->registration_number ?? 'N/A' }}</span>
                </p>
            </div>
            <a href="{{ route('supervisor.students.show', $placement->id) }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                &larr; Wasifu wa Mwanafunzi
            </a>
        </div>

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="text-[10px] uppercase font-bold text-slate-400">Asilimia ya Mahudhurio</span>
                <p class="text-2xl font-black text-blue-600 mt-1">{{ $summary['attendance_percentage'] ?? 0 }}%</p>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, $summary['attendance_percentage'] ?? 0) }}%"></div>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="text-[10px] uppercase font-bold text-slate-400">Siku Zilizorekodiwa</span>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $summary['present_days'] ?? 0 }}</p>
                <span class="text-[10px] text-emerald-600 font-semibold"><i class="fa-solid fa-check mr-1"></i> Alihudhuria kikamilifu</span>
            </div>

            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="text-[10px] uppercase font-bold text-slate-400">Kuchelewa (Late)</span>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $summary['late_days'] ?? 0 }}</p>
                <span class="text-[10px] text-amber-600 font-semibold"><i class="fa-solid fa-clock mr-1"></i> Baada ya 09:00 AM</span>
            </div>

            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="text-[10px] uppercase font-bold text-slate-400">Kutohudhuria (Absent)</span>
                <p class="text-2xl font-black text-rose-600 mt-1">{{ $summary['absent_days'] ?? 0 }}</p>
                <span class="text-[10px] text-rose-600 font-semibold"><i class="fa-solid fa-xmark mr-1"></i> Siku ambazo hakufika</span>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-location-dot text-blue-600"></i>
                Rekodi za Mahudhurio kwa Eneo (GPS Check-ins)
            </h3>

            @if($attendance->isEmpty())
                <div class="py-14 text-center">
                    <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="fa-solid fa-calendar-xmark"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Hakuna Rekodi za Mahudhurio</h3>
                    <p class="text-xs text-slate-500 mt-1">Mwanafunzi bado hajafanya GPS check-in yoyote.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Tarehe</th>
                                <th class="px-4 py-3">Muda wa Kufika (Check-in)</th>
                                <th class="px-4 py-3">Hali (Status)</th>
                                <th class="px-4 py-3">Uhakiki wa GPS Coordinates</th>
                                <th class="px-4 py-3">Maelezo (Notes)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($attendance as $att)
                                <tr>
                                    <td class="px-4 py-3.5 font-bold text-slate-900 whitespace-nowrap font-mono">
                                        {{ $att->attendance_date ? \Carbon\Carbon::parse($att->attendance_date)->format('d M, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap font-mono text-slate-700">
                                        {{ $att->check_in_time ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        @if($att->status === 'present')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-check mr-1"></i> Alihudhuria
                                            </span>
                                        @elseif($att->status === 'late')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-clock mr-1"></i> Alichelewa
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-xmark mr-1"></i> Hakuhudhuria
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 font-mono text-[11px] text-slate-500">
                                        @if($att->latitude && $att->longitude)
                                            <span class="inline-flex items-center gap-1 text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                                <i class="fa-solid fa-map-pin text-[10px]"></i>
                                                {{ round($att->latitude, 4) }}, {{ round($att->longitude, 4) }}
                                            </span>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600 text-[11px]">
                                        {{ $att->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $attendance->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
