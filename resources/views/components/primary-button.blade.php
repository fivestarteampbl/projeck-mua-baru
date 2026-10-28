<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-[#5A1F2E] border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-[#401322] focus:outline-none focus:ring-2 focus:ring-[#C9A227] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
