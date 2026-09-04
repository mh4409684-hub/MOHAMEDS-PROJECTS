@props(['status'])

@php
    $colors = [
        'new'               => 'bg-blue-100 text-blue-800',
        'open'              => 'bg-yellow-100 text-yellow-800',
        'in_progress'       => 'bg-purple-100 text-purple-800',
        'pending_requester' => 'bg-orange-100 text-orange-800',
        'resolved'          => 'bg-green-100 text-green-800',
        'closed'            => 'bg-gray-100 text-gray-800',
        'reopened'          => 'bg-red-100 text-red-800',
    ];
    $class = $colors[$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $class }}">
    {{ ucwords(str_replace('_', ' ', $status)) }}
</span>