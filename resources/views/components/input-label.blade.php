@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#5A1F2E]']) }}>
    {{ $value ?? $slot }}
</label>
