@extends('layouts.public')

@section('title', 'Form Pemesanan - ULFAMUZA MUA')

@section('content')
@php
    $pricelistData = [];
    foreach ($layanans as $item) {
        foreach ($item->pricelists as $paketItem) {
            $pricelistData[(string) $paketItem->id] = [
                'nama'  => $paketItem->nama_pricelist,
                'harga' => (int) $paketItem->harga,
            ];
        }
    }
    $selectedId = old('pricelist_id', $paketDipilih?->id);
@endphp
<div class="pb-16 bg-bg-form">

    <div class="max-w-7xl mx-auto px-6 lg:px-10 pt-8 lg:pt-12">

        <a href="{{ route('layanan.index') }}" class="inline-flex items-center gap-2 text-text-main hover:text-primary text-sm font-medium transition mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>

        {{-- Progress Stepper --}}
        <div class="mb-12">
            <div class="flex items-center justify-center gap-0 max-w-lg mx-auto">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#A32D3B] flex items-center justify-center shadow-md shadow-[#A32D3B]/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="text-center ml-3">
                        <p class="text-xs font-semibold text-[#A32D3B]">Langkah 1</p>
                        <p class="text-xs text-text-main">Data Diri</p>
                    </div>
                </div>
                <div class="w-16 h-px bg-[#F0DBD3] mx-3"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#F0DBD3] flex items-center justify-center">
                        <svg class="w-5 h-5 text-text-main" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-center ml-3">
                        <p class="text-xs font-semibold text-text-main/50">Langkah 2</p>
                        <p class="text-xs text-text-main/50">Detail Acara</p>
                    </div>
                </div>
                <div class="w-16 h-px bg-[#F0DBD3] mx-3"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#F0DBD3] flex items-center justify-center">
                        <svg class="w-5 h-5 text-text-main" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-center ml-3">
                        <p class="text-xs font-semibold text-text-main/50">Langkah 3</p>
                        <p class="text-xs text-text-main/50">Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-10">
            <h1 class="font-serif text-3xl lg:text-4xl text-primary font-bold">Formulir Pemesanan</h1>
            <p class="text-text-main/70 mt-2 text-sm">Lengkapi data berikut untuk melakukan pemesanan paket makeup</p>
        </div>

        @if($errors->any())
        <div class="max-w-3xl mx-auto mb-8 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-danger shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-semibold mb-1">Mohon perbaiki kesalahan berikut:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- 70%-30% Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-10 lg:gap-12">

            {{-- Left: Form --}}
            <div>
                <form action="{{ route('booking.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Section: Data Diri --}}
                    <div class="bg-card-peach rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-md">
                        <h3 class="font-serif text-lg font-bold text-primary mb-6 flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-[#A32D3B] text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                            Data Diri
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="nama_pelanggan" class="block text-xs font-semibold mb-1.5 text-primary">Nama Lengkap</label>
                                <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required
                                       class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition"
                                       placeholder="Masukkan nama lengkap">
                                @error('nama_pelanggan')
                                    <p class="mt-2 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="no_hp" class="block text-xs font-semibold mb-1.5 text-primary">No. WhatsApp</label>
                                <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" required
                                       class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition"
                                       placeholder="08xxxxxxxxxxx">
                                @error('no_hp')
                                    <p class="mt-2 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-semibold mb-1.5 text-primary">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition"
                                       placeholder="Masukkan email">
                                @error('email')
                                    <p class="mt-2 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section: Detail Acara --}}
                    <div class="bg-card-peach rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-md">
                        <h3 class="font-serif text-lg font-bold text-primary mb-6 flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-[#A32D3B] text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                            Detail Acara
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="pricelist_id" class="block text-xs font-semibold mb-1.5 text-primary">Pilih Layanan / Paket</label>
                                <select name="pricelist_id" id="pricelist_id" required
                                        class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition">
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($layanans as $layananItem)
                                        <optgroup label="{{ $layananItem->nama_layanan }}">
                                            @foreach($layananItem->pricelists as $paketItem)
                                                <option value="{{ $paketItem->id }}"
                                                        data-harga="{{ $paketItem->harga }}"
                                                        @selected($selectedId == $paketItem->id)>
                                                    {{ $paketItem->nama_pricelist }} — Rp {{ number_format($paketItem->harga, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('pricelist_id')
                                    <p class="mt-2 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="tanggal_acara" class="block text-xs font-semibold mb-1.5 text-primary">Tanggal Acara</label>
                                    <input type="date" name="tanggal_acara" id="tanggal_acara" value="{{ old('tanggal_acara') }}" required
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition">
                                    @error('tanggal_acara')
                                        <p class="mt-2 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="waktu_acara" class="block text-xs font-semibold mb-1.5 text-primary">Waktu Acara</label>
                                    <input type="time" name="waktu_acara" id="waktu_acara" value="{{ old('waktu_acara') }}" required
                                           class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition">
                                    @error('waktu_acara')
                                        <p class="mt-2 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label for="lokasi_acara" class="block text-xs font-semibold mb-1.5 text-primary">Lokasi Acara</label>
                                <input type="text" name="lokasi_acara" id="lokasi_acara" value="{{ old('lokasi_acara') }}" required
                                       class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition"
                                       placeholder="Masukkan alamat lengkap">
                                @error('lokasi_acara')
                                    <p class="mt-2 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="catatan_tambahan" class="block text-xs font-semibold mb-1.5 text-primary">Catatan <span class="text-text-main/50">(Opsional)</span></label>
                                <textarea name="catatan_tambahan" id="catatan_tambahan" rows="3"
                                          class="w-full rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition"
                                          placeholder="Permintaan khusus, tema makeup, dll.">{{ old('catatan_tambahan') }}</textarea>
                                @error('catatan_tambahan')
                                    <p class="mt-2 p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-xs text-text-main/60">
                                Cek <a href="{{ route('kalender.index') }}" class="underline text-primary hover:text-primary-hover">kalender ketersediaan</a> sebelum memilih jadwal.
                            </p>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-[#A32D3B] hover:bg-[#8A2531] text-white font-semibold py-4 rounded-full text-sm transition-all duration-200 shadow-lg shadow-[#A32D3B]/25">
                        Kirim Pemesanan
                    </button>
                </form>
            </div>

            {{-- Right: Sticky Summary Card --}}
            <div class="lg:sticky lg:top-28 self-start">
                <div class="bg-card-peach rounded-card border border-[#F0DBD3] p-6 lg:p-8 shadow-xl">
                    <h4 class="font-serif text-lg font-bold text-primary mb-5">Ringkasan Pesanan</h4>

                    {{-- Empty state --}}
                    <div id="summaryEmpty" class="text-center py-8 border-2 border-dashed border-[#F0DBD3] rounded-xl">
                        <svg class="w-12 h-12 text-[#F0DBD3] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p class="text-sm text-text-main/50">Pilih paket untuk</p>
                        <p class="text-sm text-text-main/50">melihat ringkasan</p>
                    </div>

                    {{-- Selected package --}}
                    <div id="summaryDetail" class="hidden">
                        <div class="bg-[#F8E9E3] rounded-xl p-4 mb-5">
                            <p class="text-xs text-text-main/60 uppercase tracking-[2px] font-medium mb-1">Paket Dipilih</p>
                            <p id="summaryNamaPaket" class="font-serif font-bold text-primary"></p>
                        </div>
                    </div>

                    <hr class="border-[#F0DBD3]/60 my-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-text-main">
                            <span>Subtotal</span>
                            <span id="summarySubtotal" class="font-semibold text-primary">—</span>
                        </div>
                        <div class="flex justify-between text-text-main">
                            <span>DP (30%)</span>
                            <span id="summaryDp" class="font-semibold text-primary">—</span>
                        </div>
                        <hr class="border-[#F0DBD3]/60">
                        <div class="flex justify-between">
                            <span class="font-semibold text-primary">Total</span>
                            <span id="summaryTotal" class="font-bold text-[#A32D3B] text-lg">—</span>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-[#F8E9E3] rounded-xl">
                        <div class="flex items-center gap-3 text-xs text-text-main/70">
                            <svg class="w-4 h-4 text-[#A32D3B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Data Anda aman dan terenkripsi</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const PRICELISTS = @json($pricelistData);
    const selectPaket = document.getElementById('pricelist_id');
    const formatRupiah = (angka) => 'Rp ' + angka.toLocaleString('id-ID');

    function updateRingkasan() {
        const id = selectPaket.value;
        const emptyBox = document.getElementById('summaryEmpty');
        const detailBox = document.getElementById('summaryDetail');
        const namaEl = document.getElementById('summaryNamaPaket');
        const subtotalEl = document.getElementById('summarySubtotal');
        const dpEl = document.getElementById('summaryDp');
        const totalEl = document.getElementById('summaryTotal');

        const paket = PRICELISTS[id];
        if (!paket) {
            emptyBox.classList.remove('hidden');
            detailBox.classList.add('hidden');
            namaEl.textContent = '';
            subtotalEl.textContent = '—';
            dpEl.textContent = '—';
            totalEl.textContent = '—';
            return;
        }

        const harga = paket.harga;
        const dp = Math.round(harga * 0.3);

        emptyBox.classList.add('hidden');
        detailBox.classList.remove('hidden');
        namaEl.textContent = paket.nama;
        subtotalEl.textContent = formatRupiah(harga);
        dpEl.textContent = formatRupiah(dp);
        totalEl.textContent = formatRupiah(harga);
    }

    if (selectPaket) {
        selectPaket.addEventListener('change', updateRingkasan);
        updateRingkasan();
    }

    const inputTanggal = document.querySelector('input[name="tanggal_acara"]');
    const inputWaktu = document.querySelector('input[name="waktu_acara"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    let jadwalTerisi = [];

    async function cekJadwalBentrok() {
        if (!inputTanggal.value || !inputWaktu.value) return;

        const tgl = new Date(inputTanggal.value + 'T00:00:00');
        const year = tgl.getFullYear();
        const month = tgl.getMonth() + 1;

        try {
            const res = await fetch(`{{ route('kalender.data') }}?year=${year}&month=${month}`);
            const data = await res.json();
            jadwalTerisi = data.events || [];
        } catch (e) {
            jadwalTerisi = [];
        }

        const waktuValue = inputWaktu.value;
        const tanggalValue = inputTanggal.value;

        const bentrok = jadwalTerisi.find(e => e.tanggal_acara === tanggalValue && e.waktu_acara === waktuValue);

        let existingMsg = document.getElementById('jadwalBentrokMsg');
        if (bentrok) {
            if (!existingMsg) {
                const div = document.createElement('div');
                div.id = 'jadwalBentrokMsg';
                div.className = 'flex items-center gap-2 text-sm text-danger bg-red-50 border border-red-200 rounded-xl px-4 py-3 mt-2';
                div.innerHTML = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Jadwal tersebut sudah terisi. Silakan pilih tanggal atau waktu lainnya.</span>';
                inputWaktu.parentNode.appendChild(div);
            }
            if (submitBtn) submitBtn.disabled = true;
        } else {
            if (existingMsg) existingMsg.remove();
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    if (inputTanggal) inputTanggal.addEventListener('change', cekJadwalBentrok);
    if (inputWaktu) inputWaktu.addEventListener('change', cekJadwalBentrok);
</script>
@endpush
