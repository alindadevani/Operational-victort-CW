@props(['color' => 'green'])

@php
    $classes = $color === 'green'
        ? 'bg-green-100 text-green-700'
        : 'bg-red-100 text-red-700';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium $classes"]) }}>
    {{ $slot }}
</span>
