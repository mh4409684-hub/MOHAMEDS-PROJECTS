<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket {{ $ticket->ticket_number }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top,_#ecfdf5,_#dff5ee_32%,_#e1f3f7_70%,_#f5f9f8_100%)] text-slate-800">
    <div class="mx-auto w-full max-w-[1500px] px-3 py-6 sm:px-5 lg:px-8">
        <div class="overflow-hidden rounded-[28px] border border-emerald-200/80 bg-white/90 shadow-[0_25px_60px_rgba(15,118,110,0.12)] backdrop-blur-sm">
            <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-amber-700 px-6 py-6 text-white">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-300">Geological Survey of Tanzania</p>
                        <h1 class="mt-2 text-3xl font-bold">{{ $ticket->subject }}</h1>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="inline-flex items-center rounded-full border border-white/20 bg-white/5 px-4 py-2 text-sm font-medium text-white hover:bg-white/10">
                        &larr; Back to tickets
                    </a>
                </div>
            </div>

            <div class="p-5 sm:p-7 lg:p-8">
                <div class="mb-5 flex flex-wrap items-center gap-3">
                    <span class="rounded-full bg-slate-900 px-3 py-1 text-sm font-semibold text-white">{{ $ticket->ticket_number }}</span>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">{{ ucfirst($ticket->priority) }}</span>
                    <span class="rounded-full bg-sky-100 px-3 py-1 text-sm font-semibold text-sky-700">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                </div>

                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Progress</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">Ticket age: {{ $ticket->created_at->diffForHumans(now(), true) }}</p>
                        </div>
                        <div class="rounded-full bg-emerald-600 px-3 py-1 text-sm font-semibold text-white">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                @if(auth()->user() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Staff Member')))
                    <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Operator action</p>
                                <p class="mt-2 text-lg font-bold text-slate-900">Update ticket status</p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <form method="POST" action="{{ route('tickets.status.update', $ticket) }}">
                                @csrf
                                <input type="hidden" name="status" value="seen">
                                <button type="submit" class="rounded-full bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-500">Seen</button>
                            </form>

                            <form method="POST" action="{{ route('tickets.status.update', $ticket) }}">
                                @csrf
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="rounded-full bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-500">In Progress</button>
                            </form>

                            <form method="POST" action="{{ route('tickets.status.update', $ticket) }}">
                                @csrf
                                <input type="hidden" name="status" value="pending_requester">
                                <button type="submit" class="rounded-full bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-400">Pending</button>
                            </form>

                            <form method="POST" action="{{ route('tickets.status.update', $ticket) }}">
                                @csrf
                                <input type="hidden" name="status" value="done">
                                <button type="submit" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">Done</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-medium text-slate-500">Category</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $ticket->category->name ?? 'Unknown' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-medium text-slate-500">Expected waiting time</p>
                        @php
                            $priorityWaitTimes = [
                                'low' => '4-6 days',
                                'medium' => '2-3 days',
                                'high' => '1-2 days',
                                'critical' => 'few hours',
                            ];
                        @endphp
                        <p class="mt-2 text-lg font-semibold text-slate-900">
                            {{ $priorityWaitTimes[$ticket->priority] ?? 'As scheduled' }}
                        </p>
                        <div class="mt-4">
                            <x-sla-countdown :ticket="$ticket" />
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5 lg:p-6">
                    <p class="text-sm font-medium text-slate-500">Description</p>
                    <p class="mt-3 whitespace-pre-line text-base leading-8 text-slate-800">{{ $ticket->description }}</p>
                </div>

                <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-white p-5 text-center text-slate-500">
                    Comments and status history will appear here in future updates.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
