@props([
    'sidebar' => false,
])

@php
    $brandName = config('app.name', 'Geological Survey of Tanzania');
@endphp

@if($sidebar)
    <flux:sidebar.brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-emerald-700 text-white shadow-sm">
            <svg viewBox="0 0 64 64" class="h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="GST logo">
                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="2"/>
                <path d="M18 42C24 31 27 25 32 18C38 25 41 31 46 42" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <path d="M22 36H42" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <path d="M32 18V46" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <circle cx="32" cy="32" r="3" fill="currentColor"/>
            </svg>
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-emerald-700 text-white shadow-sm">
            <svg viewBox="0 0 64 64" class="h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="GST logo">
                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="2"/>
                <path d="M18 42C24 31 27 25 32 18C38 25 41 31 46 42" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <path d="M22 36H42" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <path d="M32 18V46" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <circle cx="32" cy="32" r="3" fill="currentColor"/>
            </svg>
        </x-slot>
    </flux:brand>
@endif
