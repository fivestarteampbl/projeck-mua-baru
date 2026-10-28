@extends('layouts.admin')
@section('title', 'Laporan')
@section('page-title', 'Laporan Booking')

@section('content')
{{-- Filter --}}
<form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <select name="bulan" class="border border-dark/20 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-dark">
        @for($m = 1; $m <= 12; $m++)
        <option value="{{ $m }}" @selected($bulan == $m)>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
        @endfor
    </select>
    <select name="tahun" class="border border-dark/20 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-dark">
        @for($y = now()->year; $y >= now()->year - 3; $y--)
        <option value="{{ $y }}" @selected($tahun == $y)>{{ $y }}</option>
        @endfor
    </select>
    <button type="submit" class="bg-dark text-white px-4 py-2 rounded-lg text-sm font-semibold">Filter</button>
</form>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 mb-8">
    @foreach([
        ['label'=>'Total Booking', 'value'=>$total, 'icon'=>'layers'],
        ['label'=>'Pendapatan',    'value'=>'Rp '.number_format($pendapatan, 0, ',', '.'), 'icon'=>'dollar-sign'],
        ['label'=>'Pending',       'value'=>$perStatus['pending'] ?? 0, 'icon'=>'alert-circle'],
        ['label'=>'Confirmed',     'value'=>$perStatus['konfirmasi'] ?? 0, 'icon'=>'check-circle'],
        ['label'=>'Completed',     'value'=>$perStatus['selesai'] ?? 0, 'icon'=>'check'],
    ] as $s)
    <div class="stat-card bg-[#F7EAE4] p-4 rounded-xl shadow-sm border border-dark/5 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium opacity-70">{{ $s['label'] }}</p>
            <p class="text-lg md:text-xl font-bold mt-1">{{ $s['value'] }}</p>
        </div>
        <i data-lucide="{{ $s['icon'] }}" class="w-6 h-6 text-dark/60"></i>
    </div>
    @endforeach
</div>

{{-- Per Layanan Stats --}}
@if($perPaket->count())
<div class="mb-8">
    <h3 class="font-semibold text-sm mb-3">Statistik Per Layanan</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($perPaket as $pl)
        <div class="bg-white rounded-xl p-4 shadow-sm border border-dark/10">
            <p class="font-semibold text-sm">{{ $pl->pricelist?->nama_pricelist ?? 'N/A' }}</p>
            <div class="flex justify-between text-xs text-dark/60 mt-2">
                <span>Total: <strong class="text-dark">{{ $pl->total }}</strong></span>
                <span>Omset: <strong class="text-dark">Rp {{ number_format($pl->omset, 0, ',', '.') }}</strong></span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Tabel Booking --}}
<div class="bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px] text-sm text-left border-collapse">
            <thead>
                <tr class="bg-pearly border-b border-dark/10">
                    <th class="p-4 font-semibold">Customer</th>
                    <th class="p-4 font-semibold">Layanan</th>
                    <th class="p-4 font-semibold">Tanggal</th>
                    <th class="p-4 font-semibold">Total</th>
                    <th class="p-4 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr class="border-b border-dark/5 hover:bg-pearly/50">
                    <td class="p-4 font-medium">{{ $b->nama_pelanggan }}</td>
                    <td class="p-4">{{ $b->pricelist?->nama_pricelist }}</td>
                    <td class="p-4">{{ $b->tanggal_acara->format('d M Y') }}</td>
                    <td class="p-4"><x-money :amount="$b->total_harga" /></td>
                    <td class="p-4"><x-status-badge :status="$b->status_booking" /></td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-dark/40">Tidak ada data untuk periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
