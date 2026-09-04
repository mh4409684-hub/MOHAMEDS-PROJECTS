<x-layouts::app :title="__('Dashboard')">
    @php
        $currentUser = auth()->user();
    @endphp
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-emerald-900 via-sky-900 to-slate-950 shadow-xl">
            <div class="flex flex-col gap-6 p-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.3em] text-emerald-200">Geological Survey of Tanzania</p>
                    <h1 class="text-3xl font-bold text-white lg:text-4xl">Support Dashboard</h1>
                    <div class="mt-3 inline-flex rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-100">
                        {{ $roleLabel ?? 'Support Portal' }}
                    </div>
                </div>

                @if($currentUser && ! $currentUser->hasRole('Admin') && ! $currentUser->hasRole('Staff Member'))
                    <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-5 py-3 text-sm font-bold text-slate-900 shadow-lg transition hover:bg-amber-300">
                        + Create Ticket
                    </a>
                @else
                    <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-5 py-3 text-sm font-bold text-slate-900 shadow-lg transition hover:bg-amber-300">
                        Open Ticket Queue
                    </a>
                @endif
            </div>

            <div class="grid gap-4 p-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="text-sm text-slate-300">Pending tickets</div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-3xl font-bold text-white">{{ $pendingTickets ?? 0 }}</span>
                        <span class="rounded-full bg-amber-400/20 px-2 py-1 text-xs font-semibold text-amber-200">Active</span>
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
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Recent activity</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">Latest tickets</h2>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="rounded-full bg-slate-900 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-slate-700">View all</a>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-3">
                        <div>
                            <div class="font-semibold text-slate-900">GST-000008</div>
                            <div class="text-sm text-slate-600">Monitor not working</div>
                        </div>
                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Medium</span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-3">
                        <div>
                            <div class="font-semibold text-slate-900">GST-000007</div>
                            <div class="text-sm text-slate-600">WiFi connectivity issue</div>
                        </div>
                        <span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-700">Low</span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-3">
                        <div>
                            <div class="font-semibold text-slate-900">GST-000006</div>
                            <div class="text-sm text-slate-600">Power failure at workstation</div>
                        </div>
                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Critical</span>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Service info</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Support guidance</h2>

                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-slate-900 p-3 text-white">
                        <div class="text-sm text-slate-300">Office support</div>
                        <div class="mt-1 font-semibold">Report issues related to office equipment, printers, and workstations.</div>
                    </div>
                    <div class="rounded-2xl bg-amber-100 p-3 text-slate-800">
                        <div class="text-sm text-slate-600">IT assistance</div>
                        <div class="mt-1 font-semibold">Request help for software, passwords, file access, and system errors.</div>
                    </div>
                    <div class="rounded-2xl bg-emerald-100 p-3 text-slate-800">
                        <div class="text-sm text-slate-600">Field and network</div>
                        <div class="mt-1 font-semibold">Report connectivity, internet, and field-based technical support matters.</div>
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-600 font-bold text-white">WA</div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-red-700">Critical service alert</p>
                            <p class="mt-2 text-lg font-bold text-red-800">+255 782 691 621</p>
                            <p class="mt-1 text-sm text-red-700">For urgent technical issues, contact the support team immediately.</p>
                            <a href="https://wa.me/255782691621" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex rounded-full bg-red-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-red-500">
                                Open WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-sky-200 bg-sky-50 p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-sky-700">Feedback & advice</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">Public concerns and suggestions</h2>
                </div>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Received</span>
            </div>

            <div class="mt-5 space-y-4">
                <div class="rounded-2xl border border-sky-100 bg-white p-4">
                    <p class="text-sm font-semibold text-slate-800">The internet connection in the office is often weak, which slows down our work.</p>
                    <p class="mt-2 text-xs font-medium uppercase tracking-[0.2em] text-sky-700">Status: The feedback has been received and will be improved soon.</p>
                </div>

                <div class="rounded-2xl border border-sky-100 bg-white p-4">
                    <p class="text-sm font-semibold text-slate-800">Please improve the response time for software and system errors in the departments.</p>
                    <p class="mt-2 text-xs font-medium uppercase tracking-[0.2em] text-sky-700">Status: The feedback has been received and will be improved soon.</p>
                </div>

                <div class="rounded-2xl border border-sky-100 bg-white p-4">
                    <p class="text-sm font-semibold text-slate-800">Some computers take too long to open basic applications; support should be faster.</p>
                    <p class="mt-2 text-xs font-medium uppercase tracking-[0.2em] text-sky-700">Status: The feedback has been received and will be improved soon.</p>
                </div>
            </div>

            @if($currentUser && $currentUser->hasRole('Admin'))
                <div class="mt-5 border-t border-sky-100 pt-4">
                    <a href="{{ route('feedback.index') }}" class="inline-flex rounded-full bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-600">
                        Review feedback as admin
                    </a>
                </div>
            @elseif($currentUser && $currentUser->hasRole('Staff Member'))
                <div class="mt-5 border-t border-sky-100 pt-4">
                    <a href="{{ route('tickets.index') }}" class="inline-flex rounded-full bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-600">
                        Review staff ticket queue
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
