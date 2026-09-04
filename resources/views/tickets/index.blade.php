<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST Labor Tickets</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top,_#ecfdf5,_#dff5ee_32%,_#e1f3f7_70%,_#f5f9f8_100%)] text-slate-800">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl border border-emerald-200/80 bg-white/90 shadow-[0_25px_60px_rgba(15,118,110,0.12)] backdrop-blur-sm">
            <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-amber-700 px-6 py-6 text-white">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-300">Geological Survey of Tanzania</p>
                        <h1 class="mt-2 text-3xl font-bold">Ticket Support Portal</h1>
                    </div>
                    <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-amber-300">
                        + Create New Ticket
                    </a>
                </div>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @php
                    $selectedStatus = request('status', 'all');
                    $statusLabels = [
                        'all' => 'All tickets',
                        'pending' => 'Pending',
                        'new' => 'New',
                        'in_progress' => 'In Progress',
                        'solved' => 'Solved',
                        'resolved' => 'Solved',
                        'done' => 'Solved',
                        'closed' => 'Solved',
                    ];
                @endphp

                <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">Open Tickets</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('tickets.index') }}" class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $selectedStatus === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">All</a>
                        <a href="{{ route('tickets.index', ['status' => 'pending']) }}" class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $selectedStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700' }}">Pending</a>
                        <a href="{{ route('tickets.index', ['status' => 'solved']) }}" class="rounded-full px-3 py-1.5 text-xs font-semibold {{ in_array($selectedStatus, ['solved', 'resolved', 'done', 'closed'], true) ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-700' }}">Solved</a>
                    </div>
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Back to dashboard</a>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 text-left text-sm font-semibold uppercase tracking-wide text-slate-600">
                                <tr>
                                    <th class="px-4 py-3">Ticket</th>
                                    <th class="px-4 py-3">Subject</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3">Priority</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Time</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white text-sm text-slate-700">
                                @forelse($tickets as $ticket)
                                    @php
                                        $user = Auth::user();
                                        $canViewTicket = ! $user || $user->hasRole('Admin') || $user->hasRole('Staff Member') || $ticket->requester_id === $user?->id;

                                        $priorityClasses = [
                                            'low' => 'bg-emerald-100 text-emerald-700',
                                            'medium' => 'bg-amber-100 text-amber-700',
                                            'high' => 'bg-orange-100 text-orange-700',
                                            'critical' => 'bg-red-100 text-red-700',
                                        ];
                                        $statusClasses = [
                                            'new' => 'bg-sky-100 text-sky-700',
                                            'seen' => 'bg-indigo-100 text-indigo-700',
                                            'in_progress' => 'bg-violet-100 text-violet-700',
                                            'resolved' => 'bg-emerald-100 text-emerald-700',
                                            'done' => 'bg-emerald-100 text-emerald-700',
                                            'closed' => 'bg-emerald-100 text-emerald-700',
                                            'pending_requester' => 'bg-amber-100 text-amber-700',
                                        ];
                                        $statusLabels = [
                                            'new' => 'New',
                                            'seen' => 'Seen',
                                            'in_progress' => 'In Progress',
                                            'resolved' => 'Solved',
                                            'done' => 'Solved',
                                            'closed' => 'Solved',
                                            'pending_requester' => 'Pending',
                                        ];
                                        $timeAgo = $ticket->created_at ? $ticket->created_at->diffForHumans(now(), true) : 'just now';
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 font-bold text-slate-900">{{ $ticket->ticket_number }}</td>
                                        <td class="px-4 py-3">{{ $ticket->subject }}</td>
                                        <td class="px-4 py-3">{{ $ticket->category->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $priorityClasses[$ticket->priority] ?? 'bg-slate-100 text-slate-700' }}">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$ticket->status] ?? 'bg-slate-100 text-slate-700' }}">
                                                {{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                                {{ $timeAgo }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($canViewTicket)
                                                <a href="{{ route('tickets.show', $ticket->id) }}" class="inline-flex rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">
                                                    View
                                                </a>
                                            @else
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-400">
                                                    Hidden
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-10 text-center text-slate-500">No ticket has been submitted yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
