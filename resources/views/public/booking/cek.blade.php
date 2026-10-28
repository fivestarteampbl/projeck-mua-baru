@extends('layouts.public')

@section('title', 'Cek Booking - ULFAMUZA MUA')

@section('content')
<div class="py-16 lg:py-24">
    <div class="max-w-lg mx-auto px-6">

        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-[#F8E9E3] rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-[#A32D3B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h1 class="font-serif text-3xl lg:text-4xl text-primary font-bold">Cek Booking</h1>
            <p class="text-text-main/70 mt-2 text-sm">Masukkan kode booking untuk melihat status pesanan Anda</p>
        </div>

        <div class="bg-white rounded-card border border-[#F0DBD3] p-8 shadow-xl">
            <form action="{{ route('booking.hasil') }}" method="GET">
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-primary mb-2">
                        Kode Booking
                    </label>
                    <input type="text" name="kode_booking" required
                           placeholder="Contoh: MUA-747HZ"
                           class="w-full rounded-input border border-card-border px-4 py-3.5 text-sm bg-white focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition">
                    @error('kode_booking')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-hover text-white py-3.5 rounded-full font-semibold text-sm transition-all duration-200 shadow-lg shadow-primary/20">
                    Cek Status
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
