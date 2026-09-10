<x-layouts.cbe title="Weekly Reports - CBE Portal">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Weekly Training Reports</h1>
                <p class="text-xs text-slate-500 mt-0.5">Formal periodic progress submissions for academic evaluation.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($reports->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <i class="fa-solid fa-calendar-check text-4xl mb-3 text-slate-300"></i>
                    <p class="text-sm font-semibold text-slate-700">No weekly reports created yet</p>
                    <p class="text-xs text-slate-400 mt-1">Weekly reports are compiled automatically or upon completing each 5-day cycle.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($reports as $rep)
                        <div class="py-4 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-900 text-sm">Week {{ $rep->week_number }}</span>
                                <span class="text-xs text-slate-400 ml-2">({{ $rep->start_date?->format('M d') }} - {{ $rep->end_date?->format('M d, Y') }})</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase bg-slate-100 text-slate-700">
                                {{ $rep->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
