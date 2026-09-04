@props(['ticket'])

@php
    $priorityWindows = [
        'low' => ['days' => 6],
        'medium' => ['days' => 3],
        'high' => ['days' => 2],
        'critical' => ['hours' => 12],
    ];

    $statusClosed = in_array($ticket->status, ['done', 'resolved', 'closed'], true);
    $priorityKey = strtolower($ticket->priority ?? 'medium');
    $window = $priorityWindows[$priorityKey] ?? ['days' => 3];

    $createdAt = $ticket->created_at ?? now();
    $dueAt = $createdAt->copy();

    if (isset($window['days'])) {
        $dueAt = $dueAt->addDays($window['days']);
    }

    if (isset($window['hours'])) {
        $dueAt = $dueAt->addHours($window['hours']);
    }

    $totalSeconds = max(1, $createdAt->diffInSeconds($dueAt));
    $elapsedSeconds = $createdAt->diffInSeconds(now());
    $remainingSeconds = max(0, $dueAt->diffInSeconds(now(), false));
    $isPast = $dueAt->isPast();
    $progressPercent = min(100, max(0, ($elapsedSeconds / $totalSeconds) * 100));
    $days = intdiv($remainingSeconds, 86400);
    $hours = intdiv(($remainingSeconds % 86400), 3600);
    $minutes = intdiv(($remainingSeconds % 3600), 60);
    $seconds = $remainingSeconds % 60;

    if ($statusClosed) {
        $countdownText = $ticket->status === 'closed' ? 'Closed' : 'Done';
        $progressPercent = 100;
    } else {
        $countdownText = $isPast ? 'Expired' : sprintf('%dd %02dh %02dm %02ds', $days, $hours, $minutes, $seconds);
    }
@endphp

<div class="w-full">
    <div class="mb-2 flex items-center justify-between gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500">
        <span>SLA countdown</span>
        <span id="ticket-countdown-{{ $ticket->id }}" class="{{ $statusClosed ? 'text-emerald-600' : ($isPast ? 'text-red-600' : 'text-emerald-600') }}">
            {{ $countdownText }}
        </span>
    </div>

    <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-200">
        <div id="ticket-progress-{{ $ticket->id }}" class="h-full rounded-full {{ $statusClosed ? 'bg-emerald-500' : ($isPast ? 'bg-red-500' : 'bg-emerald-500') }} transition-all duration-700" style="width: {{ $progressPercent }}%"></div>
    </div>

    <script>
        (function () {
            const countdownEl = document.getElementById('ticket-countdown-{{ $ticket->id }}');
            const progressEl = document.getElementById('ticket-progress-{{ $ticket->id }}');
            const isClosed = {{ $statusClosed ? 'true' : 'false' }};

            if (isClosed) {
                countdownEl.textContent = '{{ $countdownText }}';
                countdownEl.className = 'text-emerald-600';
                progressEl.className = 'h-full rounded-full bg-emerald-500 transition-all duration-700';
                progressEl.style.width = '100%';
                return;
            }

            const dueAt = new Date('{{ $dueAt->toIso8601String() }}');
            const createdAt = new Date('{{ $createdAt->toIso8601String() }}');
            const totalSeconds = {{ $totalSeconds }};

            function updateCountdown() {
                const now = new Date();
                const remainingMs = dueAt.getTime() - now.getTime();
                const elapsed = Math.max(0, (now.getTime() - createdAt.getTime()) / 1000);
                const progress = Math.min(100, Math.max(0, (elapsed / totalSeconds) * 100));
                progressEl.style.width = progress + '%';

                if (remainingMs <= 0) {
                    countdownEl.textContent = 'Expired';
                    countdownEl.className = 'text-red-600';
                    progressEl.className = 'h-full rounded-full bg-red-500 transition-all duration-700';
                    return;
                }

                const totalSecondsLeft = Math.max(0, Math.ceil(remainingMs / 1000));
                const days = Math.floor(totalSecondsLeft / 86400);
                const hours = Math.floor((totalSecondsLeft % 86400) / 3600);
                const minutes = Math.floor((totalSecondsLeft % 3600) / 60);
                const seconds = totalSecondsLeft % 60;

                countdownEl.textContent = days + 'd ' + String(hours).padStart(2, '0') + 'h ' + String(minutes).padStart(2, '0') + 'm ' + String(seconds).padStart(2, '0') + 's';
                countdownEl.className = 'text-emerald-600';
                progressEl.className = 'h-full rounded-full bg-emerald-500 transition-all duration-700';
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();
    </script>
</div>