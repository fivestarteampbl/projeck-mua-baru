@extends('layouts.public')

@section('title', 'Layanan - ULFAMUZA MUA')

@section('content')
<div class="pb-16 bg-bg-krem">

    <div class="max-w-7xl mx-auto px-6 lg:px-10 pt-8 lg:pt-12">

        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-text-main hover:text-primary text-sm font-medium transition mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>

        <div class="text-center mb-10">
            <div class="flex items-center justify-center gap-5 mb-4">
                <div class="h-px w-24 sm:w-40 bg-gradient-to-r from-transparent to-text-main/60"></div>
                <span class="text-text-main text-xs font-semibold uppercase tracking-[1.2px]">Katalog</span>
                <div class="h-px w-24 sm:w-40 bg-gradient-to-l from-transparent to-text-main/60"></div>
            </div>
            <h1 class="font-serif text-3xl lg:text-4xl text-primary font-bold">Layanan Kami</h1>
            <p class="text-text-main/70 mt-3 max-w-lg mx-auto text-sm">
                Pilih layanan untuk melihat paket makeup yang tersedia.
            </p>
        </div>

        {{-- Daftar Paket per Layanan --}}
        @forelse($layanans as $layanan)
        <section class="mb-16 last:mb-0">
            <div class="flex items-center gap-4 mb-8">
                <span class="w-1.5 h-10 rounded-full bg-card-alt"></span>
                <div>
                    <h2 class="font-serif text-2xl lg:text-3xl text-primary leading-none">{{ $layanan->nama_layanan }}</h2>
                    <p class="text-xs text-text-muted mt-2">{{ $layanan->pricelists->count() }} Paket Tersedia</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($layanan->pricelists as $paket)
                <div class="group flex flex-col bg-white rounded-card border border-card-border overflow-hidden shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="relative h-48 overflow-hidden bg-card-alt-2">
                        @if($paket->foto_pricelist)
                            <img src="{{ Storage::url($paket->foto_pricelist) }}"
                                 alt="{{ $paket->nama_pricelist }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-card-peach">
                                <span class="font-serif text-3xl text-primary/70">{{ Str::upper(Str::substr($paket->nama_pricelist, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/60 via-transparent to-transparent"></div>
                        <span class="absolute bottom-4 left-4 inline-flex items-center px-3 py-1 rounded-full bg-card-alt-2 text-primary text-xs font-semibold">
                            {{ $layanan->nama_layanan }}
                        </span>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="font-serif text-lg font-bold text-primary">{{ $paket->nama_pricelist }}</h3>

                        @if($paket->deskripsi)
                            <p class="text-sm text-text-main/70 leading-relaxed mt-2 line-clamp-3">{{ $paket->deskripsi }}</p>
                        @endif

                        <div class="mt-5 pt-4 border-t border-card-alt-3 flex-1 flex items-end">
                            <p class="font-serif text-2xl font-bold text-[#A32D3B]">
                                <x-money :amount="$paket->harga" />
                            </p>
                        </div>

                        <a href="{{ route('pricelist.show', $paket) }}"
                           class="block w-full text-center mt-5 bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                            Lihat Detail &amp; Pesan
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @empty
        <div class="text-center py-20 bg-white rounded-card border border-card-border">
            <div class="w-16 h-16 bg-card-peach rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h2 class="font-serif text-xl text-primary font-bold mb-2">Belum ada layanan</h2>
            <p class="text-sm text-text-main/60">Layanan untuk kategori ini belum tersedia.</p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 mt-6 bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                Kembali ke Beranda
            </a>
        </div>
        @endforelse

    </div>
</div>
@endsection
