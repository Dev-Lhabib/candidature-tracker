@props(['variant' => 'default', 'label'])

@php
    $classes = match($variant) {
        'en_attente', 'default' => 'bg-sky-100 text-sky-800 ring-sky-200/60',
        'relance'             => 'bg-amber-100 text-amber-800 ring-amber-200/60',
        'entretien'           => 'bg-violet-100 text-violet-800 ring-violet-200/60',
        'offre'               => 'bg-emerald-100 text-emerald-800 ring-emerald-200/60',
        'refuse'              => 'bg-red-100 text-red-800 ring-red-200/60',
        'abandonne'           => 'bg-slate-100 text-slate-600 ring-slate-200/60',
        'haute'               => 'bg-red-100 text-red-800 ring-red-200/60',
        'moyenne'             => 'bg-amber-100 text-amber-800 ring-amber-200/60',
        'basse'               => 'bg-emerald-100 text-emerald-800 ring-emerald-200/60',
        'positif'             => 'bg-emerald-100 text-emerald-800 ring-emerald-200/60',
        'negatif'             => 'bg-red-100 text-red-800 ring-red-200/60',
        'annule'              => 'bg-slate-200 text-slate-700 ring-slate-300/60',
        default => 'bg-slate-100 text-slate-700 ring-slate-200/60',
    };
@endphp

<span {{ $attributes->merge(['class' => "badge ring-1 ring-inset {$classes}"]) }}>
    {{ $label }}
</span>
