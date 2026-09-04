<x-layouts::app :title="__('Admin Dashboard')">
    @php
        $currentUser = auth()->user();
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-950 via-sky-950 to-emerald-900 shadow-xl">
            <div class="flex flex-col gap-6 p-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.3em] text-emerald-200">Geological Survey of Tanzania</p>
                    <h1 class="text-3xl font-bold text-white lg:text-4xl">Operations Dashboard</h1>
                    <div class="mt-3 inline-flex rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-100">
                        {{ $roleLabel ?? 'Admin Operator' }}
                    </div>
                </div>

                <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-5 py-3 text-sm font-bold text-slate-900 shadow-lg transition hover:bg-amber-300">
                    Open Ticket Queue
                </a>
            </div>

            <div class="grid gap-4 p-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="text-sm text-slate-300">Active tickets</div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-3xl font-bold text-white">{{ $pendingTickets ?? 0 }}</span>
                        <span class="rounded-full bg-amber-400/20 px-2 py-1 text-xs font-semibold text-amber-200">Open</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="text-sm text-slate-300">Solved tickets</div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-3xl font-bold text-white">{{ $solvedTickets ?? 0 }}</span>
                        <span class="rounded-full bg-emerald-400/20 px-2 py-1 text-xs font-semibold text-emerald-200">Done</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="text-sm text-slate-300">Avg. response</div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-3xl font-bold text-white">1.8h</span>
                        <span class="rounded-full bg-sky-400/20 px-2 py-1 text-xs font-semibold text-sky-200">Fast</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="text-sm text-slate-300">Critical issues</div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-3xl font-bold text-white">{{ $criticalTickets ?? 0 }}</span>
                        <span class="rounded-full bg-red-400/20 px-2 py-1 text-xs font-semibold text-red-200">Watch</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.6fr_0.9fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Live queue</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">Latest tickets</h2>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="rounded-full bg-slate-900 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-slate-700">View all</a>
                </div>

                <div class="space-y-3">
                    @forelse($tickets ?? [] as $ticket)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $ticket->ticket_number }}</div>
                                <div class="text-sm text-slate-600">{{ $ticket->subject }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                            No tickets in the queue yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Operator checklist</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Today's priorities</h2>

                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-slate-900 p-3 text-white">
                        <div class="text-sm text-slate-300">Ticket review</div>
                        <div class="mt-1 font-semibold">Review all new and in-progress tickets and confirm affected service areas.</div>
                    </div>
                    <div class="rounded-2xl bg-amber-100 p-3 text-slate-800">
                        <div class="text-sm text-slate-600">Priority handling</div>
                        <div class="mt-1 font-semibold">Fast-track critical issues and connectivity faults before routine requests.</div>
                    </div>
                    <div class="rounded-2xl bg-emerald-100 p-3 text-slate-800">
                        <div class="text-sm text-slate-600">Service closure</div>
                        <div class="mt-1 font-semibold">Mark issues as done once verified by staff and the requester.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
