<x-layouts.cbe title="Daily E-Logbook - CBE Portal">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Student E-Logbook</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Log your daily duties, practical learning, challenges, and training hours.
                </p>
            </div>
            <a href="{{ route('student.elogbook.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Add Today's Entry
            </a>
        </div>

        <!-- Logbook Entries List -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($logbookEntries->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <i class="fa-solid fa-book-open text-4xl mb-3 text-slate-300"></i>
                    <p class="text-sm font-semibold text-slate-700">No logbook entries recorded yet</p>
                    <p class="text-xs text-slate-400 mt-1">Click the button above to create your first daily activity entry.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($logbookEntries as $entry)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/70 p-3 rounded-xl transition">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-slate-900">
                                        {{ $entry->activity_date ? $entry->activity_date->format('l, M d, Y') : 'Date N/A' }}
                                    </span>
                                    @if($entry->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase">Approved</span>
                                    @elseif($entry->status === 'submitted')
                                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold uppercase">Submitted</span>
                                    @elseif($entry->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-extrabold uppercase">Needs Revision</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-extrabold uppercase">Draft</span>
                                    @endif
                                    <span class="text-xs text-slate-400">&bull; {{ $entry->hours_worked }} Hours</span>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2 max-w-2xl">
                                    {{ $entry->activity_description }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-2 shrink-0">
                                <a href="{{ route('student.elogbook.show', $entry->id) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-semibold transition">
                                    View
                                </a>
                                @if($entry->status === 'draft')
                                    <a href="{{ route('student.elogbook.edit', $entry->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold transition">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('student.elogbook.submit', $entry->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition">
                                            Submit
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
