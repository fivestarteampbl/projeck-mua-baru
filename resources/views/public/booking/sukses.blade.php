@extends('layouts.public')

@section('title', 'Pemesanan Berhasil - ULFAMUZA MUA')

@section('content')
<div class="py-16 lg:py-24">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">

        {{-- Success Header --}}
        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-[#DCFCE7] rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg ring-4 ring-[#A32D3B]/10">
                <svg class="w-10 h-10 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="font-serif text-3xl lg:text-4xl text-primary font-bold">Pesanan Anda Sudah Kami Terima</h1>
            <p class="text-text-main/70 mt-3 max-w-md mx-auto text-sm">
                Terima kasih, <strong>{{ $booking->nama_pelanggan }}</strong>. Kami akan segera menghubungi kamu via WhatsApp untuk konfirmasi.
            </p>
            <div class="inline-flex items-center gap-2 mt-4 bg-[#A32D3B]/10 text-[#A32D3B] px-4 py-2 rounded-full text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                ID Pesanan: {{ $booking->kode_booking }}
            </div>
        </div>

        {{-- 2 Columns Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10 items-start">
            <div class="space-y-6">

            {{-- Card 1: Order Summary --}}
            <div class="bg-white rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-md">
                <h3 class="font-serif text-lg font-bold text-primary mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#A32D3B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Ringkasan Pesanan
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-text-main/60">Kode Booking</span>
                        <span class="font-bold text-primary">{{ $booking->kode_booking }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-main/60">Paket</span>
                        <span class="font-semibold text-primary">{{ $booking->pricelist?->nama_pricelist }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-main/60">Tanggal</span>
                        <span class="font-semibold text-primary">{{ $booking->tanggal_acara->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-main/60">Waktu</span>
                        <span class="font-semibold text-primary">{{ $booking->waktu_acara }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-main/60">Lokasi</span>
                        <span class="font-semibold text-primary text-right max-w-[200px]">{{ $booking->lokasi_acara }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Payment Details --}}
            <div class="bg-white rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-md">
                <h3 class="font-serif text-lg font-bold text-primary mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#A32D3B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Rincian Pembayaran
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-text-main/60">Total Harga</span>
                        <span class="font-semibold text-primary">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-main/60">DP (30%)</span>
                        <span class="font-semibold text-primary">Rp {{ number_format($booking->dp, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-[#F0DBD3]/60 my-2">
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-semibold">Nominal Pembayaran</span>
                        <span class="font-serif text-2xl font-bold text-[#A32D3B]">Rp {{ number_format($booking->dp, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            </div>

            <div class="space-y-6">
            {{-- Card 3: Next Steps --}}
            <div class="bg-white rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-md">
                <h3 class="font-serif text-lg font-bold text-primary mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#A32D3B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Instruksi Lanjutan
                </h3>
                <div class="space-y-4">
                    <a href="https://wa.me/6289539478239?text={{ urlencode('Halo, saya ' . $booking->nama_pelanggan . ' (Kode: ' . $booking->kode_booking . ') ingin konfirmasi pemesanan paket ' . ($booking->pricelist?->nama_pricelist ?? '-') . '. Mohon info lebih lanjut.') }}"
                       target="_blank"
                       class="block w-full text-center bg-wa hover:bg-[#20BD5A] text-white px-5 py-3.5 rounded-full font-semibold text-sm transition-all duration-200 shadow-lg shadow-wa/20 inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Konfirmasi via WhatsApp
                    </a>
                    <div class="p-4 bg-[#A32D3B]/10 border border-[#A32D3B]/30 rounded-xl">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-[#A32D3B]/15 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-[#A32D3B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-primary-hover text-xs">Menunggu Konfirmasi Admin</p>
                                <p class="text-xs text-[#A32D3B] mt-1">Booking kamu sedang diproses. Kami akan menghubungimu via WhatsApp.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        </div>

        {{-- Bottom Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('booking.hasil') }}?kode_booking={{ $booking->kode_booking }}"
               class="inline-flex items-center gap-2 bg-[#A32D3B] hover:bg-[#8A2531] text-white px-8 py-3.5 rounded-full font-semibold text-sm transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Cek Status Booking
            </a>
            <div>
                <a href="{{ route('home') }}" class="inline-block text-text-main/60 hover:text-[#A32D3B] text-sm transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        {{-- Green Success Banner --}}
        <div class="mt-8 p-4 bg-[#DCFCE7] border border-[#A32D3B]/10 rounded-2xl flex items-start gap-3">
            <svg class="w-5 h-5 text-[#16A34A] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-xs text-[#166534] leading-relaxed">
                Pesanan Anda berhasil dikirim. Simpan kode booking <strong>{{ $booking->kode_booking }}</strong> untuk mengecek status kapan saja melalui menu Cek Booking.
            </p>
        </div>

    </div>
</div>
@endsection
