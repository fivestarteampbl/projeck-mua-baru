{{-- resources/views/components/money.blade.php --}}
@props(['amount'])

<span {{ $attributes->merge(['class' => 'tabular-nums']) }}>Rp {{ number_format($amount, 0, ',', '.') }}</span>