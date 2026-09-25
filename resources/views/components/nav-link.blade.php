@props(['href', 'active' => false])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => $active
            ? 'flex items-center gap-3 px-4 py-2.5 rounded-lg bg-primary-light text-primary-dark font-medium text-sm'
            : 'flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 text-sm'
    ]) }}
>
    {{ $slot }}
</a>
