@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#DCC8C1] focus:border-[#C9A227] focus:ring-[#C9A227] rounded-lg shadow-sm text-sm']) }}>
