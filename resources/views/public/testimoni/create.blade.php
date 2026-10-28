@extends('layouts.public')

@section('title', 'Berikan Testimoni - ULFAMUZA MUA')

@section('content')
<div class="py-16 lg:py-24">
    <div class="max-w-xl mx-auto px-6">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-card-alt-2 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-[#A32D3B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h1 class="font-serif text-3xl text-primary font-bold">Berikan Testimoni</h1>
            <p class="text-text-main/70 mt-2 text-sm">Terima kasih telah menggunakan layanan ULFAMUZA MUA</p>
        </div>

        <div class="bg-white rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-md">

            {{-- Booking Info --}}
            <div class="mb-6 p-5 bg-card-alt-2 rounded-xl">
                <div class="space-y-2 text-sm">
                    <p class="font-semibold text-primary">{{ $booking->nama_pelanggan }}</p>
                    <div class="flex items-center gap-3 text-xs text-text-main/60">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $booking->kode_booking }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $booking->tanggal_acara->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('testimoni.store', $booking->token_testimoni) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-primary mb-2">Rating</label>
                    <select name="rating" required
                            class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-white focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition">
                        <option value="">Pilih Rating</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('rating') == $i)>
                                {{ str_repeat('⭐', $i) }} ({{ $i }})
                            </option>
                        @endfor
                    </select>
                    @error('rating')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-primary mb-2">Ulasan</label>
                    <textarea name="ulasan" rows="5" required maxlength="500"
                              class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-white focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition"
                              placeholder="Bagikan pengalaman Anda menggunakan layanan kami...">{{ old('ulasan') }}</textarea>
                    @error('ulasan')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-primary mb-2">Foto Anda <span class="text-text-main/50">(opsional)</span></label>
                    <input type="file" name="foto" accept="image/jpeg,image/png"
                           class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-white focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-card-alt-2 file:text-[#A32D3B] hover:file:bg-card-alt-3">
                    @error('foto')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-hover text-white py-3.5 rounded-full font-semibold text-sm transition-all duration-200 shadow-lg shadow-primary/20">
                    Kirim Testimoni
                </button>

            </form>

        </div>

    </div>
</div>
@endsection
