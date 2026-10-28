@extends('layouts.public')

@section('title', $pricelist->nama_pricelist . ' - ULFAMUZA MUA')

@section('content')

{{-- Hero Banner --}}
<section class="relative overflow-hidden">
    @if($pricelist->foto_pricelist)
        <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ Storage::url($pricelist->foto_pricelist) }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary/95 via-primary/80 to-primary/50"></div>
    @else
        <div class="absolute inset-0 bg-primary"></div>
    @endif
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-16 lg:py-20 relative">
        <a href="{{ route('layanan.index') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium transition mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <span class="block text-white/60 text-sm font-medium tracking-[3px] uppercase">Detail Paket</span>
        <h1 class="font-serif text-3xl lg:text-5xl text-white font-bold mt-3 leading-tight">{{ $pricelist->nama_pricelist }}</h1>
        @if($pricelist->deskripsi)
            <p class="text-white/70 mt-4 max-w-lg text-sm leading-relaxed">{{ $pricelist->deskripsi }}</p>
        @endif
    </div>
</section>

{{-- Content 70% - 30% --}}
<section class="max-w-7xl mx-auto px-6 lg:px-10 py-12 lg:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-10 lg:gap-12">

        {{-- Left Column --}}
        <div class="space-y-10">

            {{-- Isi Paket --}}
            @if($pricelist->isi_paket && count($pricelist->isi_paket))
            <div class="bg-white rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-md">
                <h3 class="font-serif text-xl font-bold text-primary mb-6 flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-[#A32D3B] text-white text-xs font-bold flex items-center justify-center shrink-0">&#10003;</span>
                    Isi Paket
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($pricelist->isi_paket as $item)
                        <div class="flex items-start gap-3 text-sm text-text-main bg-[#F8E9E3] border border-[#F0DBD3] rounded-xl px-4 py-3">
                            <svg class="w-4 h-4 text-[#A32D3B] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Syarat & Ketentuan --}}
            @if($pricelist->syarat_ketentuan && count($pricelist->syarat_ketentuan))
            <div class="bg-white rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-md">
                <h3 class="font-serif text-xl font-bold text-primary mb-6 flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#A32D3B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Syarat &amp; Ketentuan
                </h3>
                <ul class="space-y-3">
                    @foreach($pricelist->syarat_ketentuan as $item)
                        <li class="flex items-start gap-3 text-sm text-text-main">
                            <svg class="w-5 h-5 text-[#A32D3B] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

        </div>

        {{-- Right Column - Sticky Summary Card --}}
        <div class="lg:sticky lg:top-28 self-start">
            <div class="bg-white rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-xl">
                <div class="text-center">
                    <p class="text-xs text-text-main/60 uppercase tracking-[2px] font-medium">Total Harga</p>
                    <p class="mt-2">
                        <x-money :amount="$pricelist->harga" class="font-serif text-3xl lg:text-4xl font-bold text-[#A32D3B]" />
                    </p>
                </div>

                <hr class="border-[#F0DBD3]/60 my-6">

                <div class="space-y-3">
                    <a href="{{ route('booking.create', ['pricelist' => $pricelist->id]) }}"
                       class="block w-full text-center bg-[#A32D3B] hover:bg-[#8A2531] text-white px-6 py-3.5 rounded-full font-semibold text-sm transition-all duration-200 shadow-lg shadow-[#A32D3B]/25">
                        Pesan Sekarang
                    </a>

                    <a href="https://wa.me/6289539478239?text={{ urlencode('Halo, saya tertarik dengan paket ' . $pricelist->nama_pricelist . ' (Rp ' . number_format($pricelist->harga, 0, ',', '.') . '). Mohon informasi lebih lanjut.') }}"
                       target="_blank"
                       class="block w-full text-center bg-wa hover:bg-[#20BD5A] text-white px-6 py-3.5 rounded-full font-semibold text-sm transition-all duration-200 inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Tanya via WhatsApp
                    </a>
                </div>

                <div class="mt-6 p-4 bg-[#F8E9E3] rounded-xl">
                    <div class="flex items-center gap-3 text-xs text-text-main/70">
                        <svg class="w-4 h-4 text-[#A32D3B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Booking aman &amp; terpercaya. DP 30% untuk konfirmasi.</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
