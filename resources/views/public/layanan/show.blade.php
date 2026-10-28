@extends('layouts.public')

@section('title', $layanan->nama_layanan . ' - ULFAMUZA MUA')

@section('content')
<div class="max-w-5xl mx-auto px-6 lg:px-10 pb-16 pt-8 lg:pt-12">

    <a href="{{ route('layanan.index') }}" class="inline-flex items-center gap-2 text-text-main hover:text-primary text-sm font-medium transition mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <div class="text-center mb-10">
        @if($layanan->foto_layanan)
            <div class="relative rounded-card overflow-hidden h-48 lg:h-56 mb-8 shadow-lg">
                <div class="absolute inset-0 bg-cover bg-center"
                     style="background-image:url('{{ Storage::url($layanan->foto_layanan) }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-primary/50 to-transparent"></div>
                <div class="absolute bottom-6 left-8">
                    <span class="text-[#F0DBD3] text-sm font-medium tracking-[3px] uppercase">Layanan</span>
                    <h1 class="font-serif text-3xl lg:text-4xl text-white font-bold mt-1">{{ $layanan->nama_layanan }}</h1>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-primary to-text-main rounded-card h-48 lg:h-56 mb-8 flex items-center justify-center shadow-lg">
                <h1 class="font-serif text-3xl lg:text-4xl text-white font-bold">{{ $layanan->nama_layanan }}</h1>
            </div>
        @endif

        @if($layanan->deskripsi)
            <p class="text-text-main/70 leading-relaxed max-w-2xl mx-auto text-sm">{{ $layanan->deskripsi }}</p>
        @endif
    </div>

    @if($layanan->pricelists->count())
    @php
        $totalPaket = $layanan->pricelists->count();
        $gridClass = 'grid grid-cols-1 gap-6 ' . ($totalPaket === 1
            ? 'md:max-w-xl md:mx-auto'
            : ($totalPaket === 2
                ? 'md:grid-cols-2 md:max-w-3xl md:mx-auto'
                : 'md:grid-cols-2 lg:grid-cols-3'));
    @endphp
    <div class="{{ $gridClass }}">
        @foreach($layanan->pricelists as $paket)
        <div class="bg-white rounded-card border border-[#F0DBD3] p-6 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col">
            <h3 class="font-serif text-lg font-bold text-primary mb-2">{{ $paket->nama_pricelist }}</h3>
            <p class="mb-4">
                <x-money :amount="$paket->harga" class="font-serif text-2xl font-bold text-[#A32D3B]" />
            </p>
            @if($paket->isi_paket && count($paket->isi_paket))
            <hr class="border-[#F0DBD3]/60 mb-4">
            <ul class="space-y-2 mb-6 flex-1">
                @foreach($paket->isi_paket as $item)
                <li class="flex items-start gap-3 text-sm text-text-main/80">
                    <svg class="w-4 h-4 text-[#A32D3B] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
            @endif
            <a href="{{ route('pricelist.show', $paket) }}"
               class="block w-full text-center bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 mt-auto">
                Lihat Detail &amp; Pesan
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
