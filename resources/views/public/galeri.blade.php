@extends('layouts.public')

@section('title', 'Galeri Makeup - ULFAMUZA MUA')

@section('content')

<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-12">
            <span class="text-[#A32D3B] text-sm font-medium tracking-[3px] uppercase">Portofolio</span>
            <h1 class="font-serif text-3xl lg:text-4xl text-primary font-bold mt-3">Hasil Karya Terbaik</h1>
            <p class="text-text-main/70 mt-3 max-w-lg mx-auto text-sm">Dokumentasi hasil makeup dari berbagai acara yang telah kami tangani</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">

            @forelse($galeris as $foto)

            <div class="group bg-white rounded-card border border-[#F0DBD3] overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                <div class="relative overflow-hidden aspect-square">
                    <x-photo-thumb :path="$foto->foto" :alt="$foto->judul" size="w-full h-full" />
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>

                <div class="p-4">
                    <h3 class="font-semibold text-primary text-sm">
                        {{ $foto->judul }}
                    </h3>
                    @if($foto->kategori)
                    <p class="text-xs text-text-main/50 mt-1">
                        {{ $foto->kategori }}
                    </p>
                    @endif
                    @if($foto->deskripsi)
                    <p class="text-xs text-text-main/70 mt-2 line-clamp-2 leading-relaxed">
                        {{ $foto->deskripsi }}
                    </p>
                    @endif
                </div>

            </div>

            @empty

            <div class="col-span-full text-center py-16 text-text-main/50 text-sm">
                <div class="w-16 h-16 bg-[#F8E9E3] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#F0DBD3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                Belum ada foto galeri.
            </div>

            @endforelse

        </div>

    </div>
</section>

@endsection
