<x-layouts.cbe title="Ripoti za Wiki Zinazosubiri - CBE Supervisor">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Ripoti za Wiki za Wanafunzi (Weekly Reports)</h1>
                <p class="text-xs text-slate-500 mt-1">Kagua na uthibitishe ripoti za kila wiki zinazowasilishwa na wanafunzi uliopewa kusimamia.</p>
            </div>
            <span class="px-3.5 py-1.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold">
                <i class="fa-solid fa-clock mr-1 text-amber-600"></i> Zinazosubiri: {{ $reports->total() }}
            </span>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($reports->isEmpty())
                <div class="py-14 text-center">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Hakuna Ripoti Zinazosubiri Ukaguzi</h3>
                    <p class="text-xs text-slate-500 mt-1">Ripoti zote zilizowasilishwa zimeshakaguliwa kikamilifu.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Mwanafunzi</th>
                                <th class="px-4 py-3">Wiki ya Mafunzo</th>
                                <th class="px-4 py-3">Tarehe za Wiki</th>
                                <th class="px-4 py-3">Tarehe Iliyowasilishwa</th>
                                <th class="px-4 py-3 text-right">Hatua</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($reports as $rep)
                                <tr>
                                    <td class="px-4 py-3.5 font-bold text-slate-900">
                                        {{ $rep->fieldPlacement->student->user->name ?? 'Student' }}
                                        <span class="block text-[10px] text-slate-400 font-normal">
                                            Reg: {{ $rep->fieldPlacement->student->user->registration_number ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs">
                                            Wiki ya {{ $rep->week_number }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600 font-mono">
                                        {{ $rep->start_date ? \Carbon\Carbon::parse($rep->start_date)->format('M d') : '-' }} &ndash; {{ $rep->end_date ? \Carbon\Carbon::parse($rep->end_date)->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-500">
                                        {{ $rep->submitted_at ? \Carbon\Carbon::parse($rep->submitted_at)->format('M d, Y H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        <a href="{{ route('supervisor.reports.review', $rep->id) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                                            <i class="fa-solid fa-eye mr-1.5"></i> Kagua Ripoti &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
