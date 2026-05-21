@props(['type' => 'success', 'message'])

@php
    $styles = match($type) {
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        'error'   => 'bg-red-50 border-red-200 text-red-800',
        default   => 'bg-brand-50 border-brand-200 text-brand-800',
    };
    $icon = match($type) {
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'error'   => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        default   => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    };
@endphp

<div {{ $attributes->merge(['class' => "flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-medium {$styles}"]) }} role="alert">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
    </svg>
    <span>{{ $message }}</span>
</div>
