{{-- resources/views/components/status-badge.blade.php --}}
@php
    $warnaMap = [
        'green'  => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
        'blue'   => 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-600/20',
        'yellow' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
        'red'    => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
        'gray'   => 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/20',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'px-2.5 py-1 rounded-full text-xs font-semibold ' . $warnaMap[$warna]]) }}>
    {{ $label }}
</span>