<x-layouts.cbe title="Historia ya Logbook - CBE Supervisor">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <span class="text-xs font-semibold text-slate-400">Historia ya Kumbukumbu za Mafunzo</span>
                <h1 class="text-2xl font-black text-slate-900 mt-0.5">
                    Logbook ya {{ $placement->student->user->name ?? 'Mwanafunzi' }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Shirika: <span class="font-bold text-slate-700">{{ $placement->hostOrganization->name ?? 'N/A' }}</span> &bull; 
                    Reg: <span class="font-mono font-bold text-slate-700">{{ $placement->student->user->registration_number ?? 'N/A' }}</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('supervisor.students.show', $placement->id) }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    &larr; Wasifu wa Mwanafunzi
                </a>
            </div>
        </div>

        <!-- Entries Table / Cards -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($entries->isEmpty())
                <div class="py-14 text-center">
                    <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Hakuna Rekodi za Logbook Zilizowekwa</h3>
                    <p class="text-xs text-slate-500 mt-1">Mwanafunzi bado hajajaza taarifa yoyote ya kila siku kwenye logbook.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Tarehe ya Shughuli</th>
                                <th class="px-4 py-3">Kazi Zilizofanyika (Activities)</th>
                                <th class="px-4 py-3">Masaa</th>
                                <th class="px-4 py-3">Hali (Status)</th>
                                <th class="px-4 py-3">Maoni ya Msimamizi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($entries as $entry)
                                <tr>
                                    <td class="px-4 py-3.5 font-bold text-slate-900 whitespace-nowrap">
                                        {{ $entry->activity_date ? \Carbon\Carbon::parse($entry->activity_date)->format('d M, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 max-w-xs sm:max-w-md">
                                        <p class="line-clamp-2 text-slate-800">{{ $entry->activity_description }}</p>
                                        @if($entry->skills_learned)
                                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">
                                                <i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i> {{ $entry->skills_learned }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap font-mono font-bold text-slate-700">
                                        {{ $entry->hours_worked }} hrs
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        @if($entry->status === 'approved')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-check mr-1"></i> Imethibitishwa
                                            </span>
                                        @elseif($entry->status === 'submitted')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-clock mr-1"></i> Inasubiri Ukaguzi
                                            </span>
                                        @elseif($entry->status === 'rejected')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-xmark mr-1"></i> Imekataliwa
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-50 text-slate-700 border border-slate-200">
                                                {{ ucfirst($entry->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-500 text-[11px]">
                                        @if($entry->supervisor_feedback)
                                            <span class="text-emerald-700 italic">"{{ $entry->supervisor_feedback }}"</span>
                                        @elseif($entry->supervisor_rejection_reason)
                                            <span class="text-rose-700 italic">"{{ $entry->supervisor_rejection_reason }}"</span>
                                        @else
                                            <span class="text-slate-300">-</span>
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
