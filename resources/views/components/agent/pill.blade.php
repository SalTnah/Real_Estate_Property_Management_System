@props(['color' => 'gray'])

@php
    $styles = [
        'gray' => 'bg-gray-100 text-gray-600',
        'blue' => 'bg-blue-100 text-blue-700',
        'green' => 'bg-green-100 text-green-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'red' => 'bg-red-100 text-red-600',
        'purple' => 'bg-purple-100 text-purple-700',
    ];

    $style = $styles[$color] ?? $styles['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium $style"]) }}>
    {{ $slot }}
</span>
