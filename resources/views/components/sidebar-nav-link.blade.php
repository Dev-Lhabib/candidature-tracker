@props(['active' => false, 'href'])

@php
    $classes = $active ? 'sidebar-link-active' : 'sidebar-link';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
