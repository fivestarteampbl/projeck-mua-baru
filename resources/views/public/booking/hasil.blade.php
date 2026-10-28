@extends('layouts.public')

@section('title', 'Status Booking - ULFAMUZA MUA')

@section('content')
<div class="py-16 lg:py-24">
    <div class="max-w-3xl mx-auto px-6">

        <div class="text-center mb-10">
            <h1 class="font-serif text-3xl lg:text-4xl text-primary font-bold">Status Booking</h1>
            <p class="text-text-main/70 mt-2 text-sm">Informasi detail status pemesanan Anda</p>
        </div>

        <div id="bookingData" data-kode="{{ $booking->kode_booking }}"></div>

        {{-- Main Status Card --}}
        <div class="bg-white rounded-card border border-[#F0DBD3] overflow-hidden shadow-md">

            {{-- Header with Kode Booking --}}
            <div class="bg-gradient-to-r from-primary to-text-main p-6 lg:p-8 text-center">
                <p class="text-[#F0DBD3]/60 text-xs uppercase tracking-[3px] font-medium">Kode Booking</p>
                <h2 class="font-serif text-2xl lg:text-3xl text-white font-bold mt-1 tracking-wider">{{ $booking->kode_booking }}</h2>
            </div>

            <div class="p-6 lg:p-8 space-y-5">

                {{-- Status Badge --}}
                <div class="flex justify-center">
                    @if($booking->status_booking->value === 'pending')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                            Menunggu Konfirmasi
                        </span>
                    @elseif($booking->status_booking->value === 'konfirmasi')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            Dikonfirmasi
                        </span>
                    @elseif($booking->status_booking->value === 'selesai')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-success-bg text-success-text text-xs font-semibold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ $booking->status_booking->label() }}
                        </span>
                    @endif
                </div>

                <hr class="border-[#F0DBD3]/60">

                {{-- Detail Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-text-main/60 text-xs">Nama Pelanggan</p>
                        <p class="font-semibold text-primary">{{ $booking->nama_pelanggan }}</p>
                    </div>
                    <div>
                        <p class="text-text-main/60 text-xs">Layanan</p>
                        <p class="font-semibold text-primary">{{ $booking->pricelist?->nama_pricelist }}</p>
                    </div>
                    <div>
                        <p class="text-text-main/60 text-xs">Tanggal Acara</p>
                        <p class="font-semibold text-primary">{{ $booking->tanggal_acara->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-text-main/60 text-xs">Lokasi</p>
                        <p class="font-semibold text-primary">{{ $booking->lokasi_acara }}</p>
                    </div>
                </div>

                <hr class="border-[#F0DBD3]/60">

                {{-- Payment Info --}}
                <div>
                    <h4 class="font-serif text-lg font-bold text-primary mb-4">Informasi Pembayaran</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-text-main/60">Total Harga</span>
                            <span class="font-semibold text-primary">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-main/60">DP (30%)</span>
                            <span class="font-semibold text-primary">Rp {{ number_format($booking->dp, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-main/60">Sisa Pembayaran</span>
                            <span class="font-semibold text-danger">Rp {{ number_format($booking->total_harga - $booking->dp, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-text-main/60">Status Pembayaran</span>
                            @if($booking->status_pembayaran->value === 'lunas')
                                <span class="px-3 py-1 rounded-full bg-success-bg text-success-text text-xs font-semibold">Lunas</span>
                            @elseif($booking->status_pembayaran->value === 'dp')
                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">DP Dibayar</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Belum DP</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Payment QR Section (shown when booking is confirmed) --}}
        @if($booking->status_booking->value === 'konfirmasi')
        <div class="mt-6 bg-white rounded-card border border-green-200 overflow-hidden shadow-md">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 text-center border-b border-green-100">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                    <svg class="w-7 h-7 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-serif text-xl text-primary font-bold">Booking Dikonfirmasi!</h3>
                <p class="text-xs text-text-main/70 mt-1">Silakan lakukan pembayaran DP untuk mengamankan jadwal Anda</p>
            </div>
            <div class="p-6 lg:p-8 text-center">
                <p class="text-sm font-semibold text-primary mb-3">Scan QR Code untuk Pembayaran DP</p>
                <p class="font-serif text-2xl font-bold text-green-700 mb-5">Rp {{ number_format($booking->dp, 0, ',', '.') }}</p>

                @if($booking->payment_qr && \Illuminate\Support\Facades\Storage::disk('public')->exists($booking->payment_qr))
                    <img src="{{ asset('storage/' . $booking->payment_qr) }}"
                         class="w-48 h-48 mx-auto mb-5 rounded-xl shadow-md border-2 border-green-200"
                         alt="QR Pembayaran">
                @else
                    <div class="w-48 h-48 mx-auto mb-5 rounded-xl bg-gray-50 flex items-center justify-center border-2 border-dashed border-gray-300">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <p class="text-xs text-gray-400">QR Code akan tersedia segera</p>
                        </div>
                    </div>
                @endif

                @if($booking->metode_pembayaran)
                    <p class="text-xs text-text-main/60 mb-5">Metode: <span class="font-semibold">{{ $booking->metode_pembayaran }}</span></p>
                @endif

                <a href="https://wa.me/6289539478239?text={{ urlencode('Halo, saya ' . $booking->nama_pelanggan . ' (Kode: ' . $booking->kode_booking . ') ingin konfirmasi pembayaran DP sebesar Rp ' . number_format($booking->dp, 0, ',', '.') . '. Mohon diproses.') }}"
                   target="_blank"
                   class="inline-flex items-center justify-center gap-2 bg-wa hover:bg-[#20BD5A] text-white px-8 py-3.5 rounded-full font-semibold text-sm transition-all duration-200 shadow-lg shadow-wa/20 w-full">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Konfirmasi Pembayaran via WhatsApp
                </a>
                <p class="text-xs text-text-main/50 mt-3">Setelah transfer, klik tombol di atas untuk konfirmasi ke admin</p>
            </div>
        </div>
        @endif

        {{-- Selesai Status - Show Testimoni Link --}}
        @if($booking->status_booking->value === 'selesai')
        <div class="mt-6 bg-white rounded-card border border-green-200 overflow-hidden shadow-md">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 text-center">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                    <svg class="w-7 h-7 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-serif text-xl text-primary font-bold">Acara Selesai!</h3>
                <p class="text-xs text-text-main/70 mt-1">Terima kasih telah menggunakan layanan ULFAMUZA MUA</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-sm text-text-main/70 mb-4">Bagikan pengalaman Anda dengan memberikan testimoni</p>
                @if($booking->tokenTestimoniValid())
                    <a href="{{ route('testimoni.create', $booking->token_testimoni) }}"
                       class="inline-flex items-center gap-2 bg-primary hover:bg-primary-hover text-white px-8 py-3.5 rounded-full font-semibold text-sm transition-all duration-200 shadow-lg shadow-primary/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Berikan Testimoni
                    </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Batal Status --}}
        @if(in_array($booking->status_booking->value, ['dibatalkan', 'ditolak'], true))
        <div class="mt-6 bg-red-50 border border-red-200 rounded-card p-8 text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p class="text-sm font-semibold text-red-700">Booking ini telah {{ $booking->status_booking->label() }}</p>
            <p class="text-xs text-danger mt-1">Silakan hubungi admin untuk informasi lebih lanjut.</p>
        </div>
        @endif

    </div>
</div>

@if($booking->status_booking->value === 'pending' || ($booking->status_booking->value === 'konfirmasi' && !$booking->payment_qr))
@push('scripts')
<script>
(function() {
    const kode = '{{ $booking->kode_booking }}';
    let counter = 0;
    const targetStatus = '{{ $booking->status_booking->value }}';

    function cekStatus() {
        fetch('{{ route("booking.hasil") }}?kode_booking=' + kode, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status_booking !== targetStatus || data.has_qr) {
                location.reload();
            }
        })
        .catch(() => {});
        counter++;
        if (counter < 60) {
            setTimeout(cekStatus, 5000);
        }
    }

    setTimeout(cekStatus, 5000);
})();
</script>
@endpush
@endif
@endsection
