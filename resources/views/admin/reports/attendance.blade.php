<x-layouts.cbe title="Ripoti ya Mahudhurio - CBE Portal">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <a href="{{ route('admin.attendance-reports') }}" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Chagua Kozi Nyingine
                </a>
                <h1 class="text-2xl font-black text-slate-900">{{ $course->name }} ({{ $course->code }})</h1>
                <p class="text-xs text-slate-500 mt-0.5">Section: {{ $section->name }} &bull; Jumla ya Vipindi: {{ $sessions->count() }}</p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print Ripoti
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($sessions->isEmpty())
                <p class="text-xs text-slate-400 py-10 text-center">Hakuna vipindi vya masomo vilivyopatikana kwa kozi hii bado.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Tarehe ya Kipindi</th>
                                <th class="px-4 py-3">Muda</th>
                                <th class="px-4 py-3">Jumla ya Waliohudhuria</th>
                                <th class="px-4 py-3">Hali (Status)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($sessions as $sess)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $sess->session_date ? \Carbon\Carbon::parse($sess->session_date)->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-slate-600">
                                        {{ $sess->start_time ?? '-' }} &ndash; {{ $sess->end_time ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 font-bold text-blue-600">
                                        {{ $sess->attendances->where('status', 'present')->count() }} Students
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                                            {{ $sess->status ?? 'Completed' }}
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
</x-layouts.cbe>
