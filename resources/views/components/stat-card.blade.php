@props(['label', 'value', 'color' => 'green'])

@php
    $colorMap = [
        'green' => 'bg-primary-light text-primary-dark',
        'blue' => 'bg-blue-100 text-blue-700',
        'red' => 'bg-red-100 text-red-700',
        'yellow' => 'bg-yellow-100 text-yellow-700',
        'gray' => 'bg-gray-100 text-gray-700',
    ];
    $iconBg = $colorMap[$color] ?? $colorMap['green'];
@endphp

<div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm">
    <div class="w-12 h-12 rounded-lg {{ $iconBg }} flex items-center justify-center shrink-0">
        {{ $icon ?? '' }}
    </div>
    <div>
        <p class="text-sm text-gray-500">{{ $label }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ $value }}</p>
    </div>
</div>
