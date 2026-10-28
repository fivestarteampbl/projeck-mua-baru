@extends('layouts.public')

@section('title', 'ULFAMUZA MUA - Make Up Banyuwangi')

@push('styles')
<style>
    .hero-clip { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); }
    @media (min-width: 768px) {
        .hero-clip { clip-path: polygon(12% 0, 100% 0, 100% 100%, 0 100%); }
    }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="relative min-h-[520px] flex items-center overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-105"
         style="background-image:url(' {{ asset('images/background.png') }} ');">
    </div>
    <div class="relative z-10 w-full max-w-[1440px] mx-auto px-6 lg:px-12 py-16">
        <div class="max-w-xl">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-[83px] h-0.5 rounded-full bg-gradient-to-r from-[#D6BD9F] to-[#B89A72]"></div>
                <span class="uppercase text-xs font-semibold tracking-[1.2px] text-card-alt-2">
                    Professional Make Up Artist
                </span>
            </div>
            <h1 class="font-serif text-[44px] sm:text-[54px] lg:text-[64px] lg:leading-[70px] leading-[1.1] text-[#F9F6F1]">
                Make Your Special Day Perfect
            </h1>
            <p class="mt-5 text-card-alt-2 text-base leading-[27px] max-w-[461px]">
                Percayakan riasan hari spesial Anda kepada ULFAMUZA —
                MUA profesional dengan pengalaman ratusan klien
                bahagia di seluruh Jawa Timur.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('layanan.index') }}"
                   class="bg-[#480C18] hover:bg-[#36090F] text-white h-10 px-4 rounded-[5px] font-medium text-sm transition-all duration-300 inline-flex items-center gap-2">
                    Lihat Paket
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="{{ route('galeri.index') }}"
                   class="bg-[#D9D9D9] hover:bg-white text-[#480C18] h-10 px-4 rounded-[5px] font-medium text-sm transition-all duration-200 inline-flex items-center">
                    Galeri Hasil
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Kategori Acara --}}
<section class="bg-[#FAF5F2] py-16 lg:py-20 px-6">
    <div class="max-w-[1440px] mx-auto">
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-5 mb-4">
                <div class="h-px w-32 sm:w-56 bg-gradient-to-r from-transparent to-[#4A3F3A]/60"></div>
                <span class="text-[#4A3F3A] text-xs font-semibold uppercase tracking-[1.2px]">Layanan Kami</span>
                <div class="h-px w-32 sm:w-56 bg-gradient-to-l from-transparent to-[#4A3F3A]/60"></div>
            </div>
            <h2 class="font-serif text-[32px] lg:text-[40px] leading-none text-[#2E2320]">Pilih Kategori Acara</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @forelse($layanans as $layanan)
                <a href="{{ route('layanan.show', $layanan) }}"
                   class="group block relative h-[408px] rounded-lg overflow-hidden border-2 border-[#6B4E3B] shadow-[8px_8px_8px_rgba(0,0,0,0.25)]">
                    @if($layanan->foto_layanan)
                        <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700"
                             style="background-image:url('{{ Storage::url($layanan->foto_layanan) }}')"></div>
                    @else
                        <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700"
                             style="background-image:url('https://picsum.photos/seed/{{ Str::slug($layanan->nama_layanan) }}/600/800')"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-b from-[#480C18]/70 via-transparent to-[#480C18]/60"></div>
                    <div class="absolute top-6 left-4 right-4">
                        <h3 class="font-serif text-[32px] lg:text-[40px] leading-none text-card-alt-2">{{ $layanan->nama_layanan }}</h3>
                        <p class="text-card-alt-2 text-[13px] font-medium mt-3">{{ $layanan->pricelists->count() }} Paket Tersedia</p>
                    </div>
                    <span class="absolute bottom-4 right-4 bg-[#480C18] hover:bg-[#36090F] text-card-alt-2 h-10 px-3 rounded-[5px] text-sm font-medium inline-flex items-center gap-1.5 transition-colors">
                        Lihat Paket
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </span>
                </a>
            @empty
                @foreach(['Wedding', 'Wisuda', 'Henna Art'] as $cat)
                <a href="{{ route('layanan.index') }}"
                   class="group block relative h-[408px] rounded-lg overflow-hidden border-2 border-[#6B4E3B] shadow-[8px_8px_8px_rgba(0,0,0,0.25)]">
                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700"
                         style="background-image:url('https://picsum.photos/seed/{{ Str::slug($cat) }}/600/800')"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-[#480C18]/70 via-transparent to-[#480C18]/60"></div>
                    <div class="absolute top-6 left-4 right-4">
                        <h3 class="font-serif text-[32px] lg:text-[40px] leading-none text-card-alt-2">{{ $cat }}</h3>
                        <p class="text-card-alt-2 text-[13px] font-medium mt-3">Paket Tersedia</p>
                    </div>
                    <span class="absolute bottom-4 right-4 bg-[#480C18] hover:bg-[#36090F] text-card-alt-2 h-10 px-3 rounded-[5px] text-sm font-medium inline-flex items-center gap-1.5 transition-colors">
                        Lihat Paket
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </span>
                </a>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- Strip Copyright --}}
<div class="bg-[#480C18] h-14 flex items-center justify-center px-6">
    <p class="text-[#FEFCFA] text-xs font-normal text-center">&copy; {{ date('Y') }} ULFAMUZA Make Up Artist. All rights reserved.</p>
</div>

{{-- Portofolio --}}
<section class="bg-[#EFD9CF] py-16 lg:py-20 px-6">
    <div class="max-w-[1440px] mx-auto">
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-5 mb-4">
                <div class="h-px w-32 sm:w-56 bg-gradient-to-r from-transparent to-[#4A3F3A]/60"></div>
                <span class="text-[#4A3F3A] text-xs font-semibold uppercase tracking-[1.2px]">Portofolio</span>
                <div class="h-px w-32 sm:w-56 bg-gradient-to-l from-transparent to-[#4A3F3A]/60"></div>
            </div>
            <h2 class="font-serif text-[32px] lg:text-[40px] leading-none text-[#2E2320]">Hasil Riasan Terbaik Kami</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-8 xl:gap-[112px] max-w-[1214px] mx-auto">
            @forelse($galeris->take(6) as $foto)
                <div class="group relative rounded-lg overflow-hidden bg-white aspect-[330/208] shadow-sm">
                    <img src="{{ Storage::url($foto->foto) }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         alt="{{ $foto->judul }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#480C18]/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                        <div>
                            <h4 class="text-white font-semibold text-sm">{{ $foto->judul }}</h4>
                            @if($foto->deskripsi)
                                <p class="text-white/70 text-xs mt-0.5">{{ Str::limit($foto->deskripsi, 50) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                @foreach(['portfolio1','portfolio2','portfolio3','portfolio4','portfolio5','portfolio6'] as $i => $seed)
                <div class="group relative rounded-lg overflow-hidden bg-white aspect-[330/208] shadow-sm">
                    <img src="https://picsum.photos/seed/{{ $seed }}/660/416"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         alt="Portfolio {{ $i + 1 }}">
                </div>
                @endforeach
            @endforelse
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('galeri.index') }}"
               class="inline-flex items-center gap-2 bg-[#480C18] hover:bg-[#36090F] text-white h-10 px-4 rounded-[5px] text-sm font-medium transition-all duration-200 inline-flex items-center">
                Lihat Semua Galeri
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- Strip Copyright --}}
<div class="bg-[#480C18] h-14 flex items-center justify-center px-6">
    <p class="text-[#FEFCFA] text-xs font-normal text-center">&copy; {{ date('Y') }} ULFAMUZA Make Up Artist. All rights reserved.</p>
</div>

{{-- Testimoni --}}
@if($testimonis->count())
<section class="bg-[#FAF5F2] py-16 lg:py-20 px-6">
    <div class="max-w-[1440px] mx-auto">
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-5 mb-4">
                <div class="h-px w-32 sm:w-56 bg-gradient-to-r from-transparent to-[#4A3F3A]/60"></div>
                <span class="text-[#4A3F3A] text-xs font-semibold uppercase tracking-[1.2px]">Testimoni</span>
                <div class="h-px w-32 sm:w-56 bg-gradient-to-l from-transparent to-[#4A3F3A]/60"></div>
            </div>
            <h2 class="font-serif text-[32px] lg:text-[40px] leading-none text-[#2E2320]">Kata Mereka</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-[1038px] mx-auto">
            @foreach($testimonis->take(4) as $t)
            @php $inisial = strtoupper(substr($t->nama_pelanggan, 0, 1)); @endphp
            <div class="bg-card-alt-2 border border-card-alt rounded-2xl p-6 shadow-[0px_4px_8px_rgba(0,0,0,0.15),0px_4px_16px_rgba(131,98,75,0.1)]">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $t->rating ? 'text-[#D6BD9F]' : 'text-card-alt' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-[#4A3F3A] text-sm leading-6 mb-5">"{{ $t->ulasan }}"</p>
                <div class="border-t border-[#EFD9CF] pt-4 flex items-center gap-3">
                    <div class="shrink-0">
                        @if($t->foto && Storage::disk('public')->exists($t->foto))
                            <img src="{{ Storage::url($t->foto) }}"
                                 class="w-10 h-10 rounded-full object-cover" alt="{{ $t->nama_pelanggan }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-[#EFD9CF] flex items-center justify-center text-[#480C18] font-semibold text-sm border border-card-alt">
                                {{ $inisial }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#2E2320]">{{ $t->nama_pelanggan }}</p>
                        @if($t->booking?->pricelist?->layanan?->nama_layanan)
                            <p class="text-xs text-[#8E7E73]">{{ $t->booking->pricelist->layanan->nama_layanan }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
