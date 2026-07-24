@props(['status'])

@php
    $styles = [
        'Available' => 'bg-green-100 text-green-700',
        'Pending' => 'bg-amber-100 text-amber-700',
        'Sold' => 'bg-red-100 text-red-600',
        'Off-market' => 'bg-gray-100 text-gray-600',
    ];

    $dots = [
        'Available' => 'bg-green-500',
        'Pending' => 'bg-amber-500',
        'Sold' => 'bg-red-500',
        'Off-market' => 'bg-gray-400',
    ];

    $style = $styles[$status] ?? 'bg-gray-100 text-gray-600';
    $dot = $dots[$status] ?? 'bg-gray-400';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium $style"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dot }}"></span>
    {{ $status }}
</span>
