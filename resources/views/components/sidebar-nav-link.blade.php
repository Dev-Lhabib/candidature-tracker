@props(['active' => false, 'href', 'count' => null])

@php
    $classes = $active ? 'sidebar-link-active' : 'sidebar-link';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes . ' justify-between']) }}>
    <span class="flex items-center gap-3 min-w-0">
        {{ $slot }}
    </span>
    @if($count !== null)
        <span class="ml-2 shrink-0 min-w-[1.5rem] rounded-md bg-white/10 px-2 py-0.5 text-center text-xs font-bold tabular-nums {{ $active ? 'bg-brand-500/30 text-white' : 'text-slate-300' }}">
            {{ $count }}
        </span>
    @endif
</a>
