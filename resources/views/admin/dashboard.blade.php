@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    @foreach([
        ['label'=>'Total Booking',    'value'=>$total,   'icon'=>'calendar-plus'],
        ['label'=>'Booking Hari Ini', 'value'=>$hariIni, 'icon'=>'clock'],
        ['label'=>'Booking Pending',  'value'=>$pending, 'icon'=>'alert-circle'],
        ['label'=>'Selesai',          'value'=>$selesai, 'icon'=>'check-circle'],
    ] as $stat)
    <div class="stat-card bg-[#F7EAE4] p-4 rounded-xl shadow-sm border border-dark/5 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium opacity-70">{{ $stat['label'] }}</p>
            <p class="text-xl md:text-2xl font-bold mt-1">{{ $stat['value'] }}</p>
        </div>
        <i data-lucide="{{ $stat['icon'] }}" class="w-6 h-6 md:w-8 md:h-8 text-dark/60"></i>
    </div>
    @endforeach
</div>

<div class="bg-[#F7EAE4] p-4 rounded-xl shadow-sm border border-dark/5 flex items-center justify-between mb-8 max-w-xs">
    <div>
        <p class="text-xs font-medium opacity-70">Pendapatan Bulan Ini</p>
        <p class="text-lg md:text-xl font-bold mt-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
    </div>
    <i data-lucide="dollar-sign" class="w-6 h-6 md:w-8 md:h-8 text-dark/60"></i>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
    {{-- Booking Terbaru --}}
    <div class="bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden">
        <div class="p-4 border-b border-dark/10 flex justify-between items-center">
            <h3 class="font-semibold text-sm">Booking Terbaru</h3>
            <a href="{{ route('admin.booking.index') }}" class="text-xs text-dark/50 hover:text-dark">Lihat Semua</a>
        </div>
        <div class="divide-y divide-dark/5">
            @forelse($bookingTerbaru as $b)
            <a href="{{ route('admin.booking.show', $b) }}" class="p-4 flex items-center justify-between text-sm hover:bg-pearly/50">
                <div class="min-w-0 flex-1 mr-2">
                    <p class="font-medium truncate">{{ $b->nama_pelanggan }}</p>
                    <p class="text-xs text-dark/50 truncate">{{ $b->pricelist?->nama_pricelist }} • {{ $b->tanggal_acara->format('d M Y') }}</p>
                </div>
                <x-status-badge :status="$b->status_booking" />
            </a>
            @empty
            <p class="p-4 text-sm text-dark/40 text-center">Belum ada booking.</p>
            @endforelse
        </div>
    </div>

    {{-- Booking Mendatang --}}
    <div class="bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden">
        <div class="p-4 border-b border-dark/10 flex justify-between items-center">
            <h3 class="font-semibold text-sm">Jadwal Mendatang</h3>
        </div>
        <div class="divide-y divide-dark/5">
            @forelse($bookingMendatang as $b)
            <a href="{{ route('admin.booking.show', $b) }}" class="p-4 flex items-center justify-between text-sm hover:bg-pearly/50">
                <div class="min-w-0 flex-1 mr-2">
                    <p class="font-medium truncate">{{ $b->nama_pelanggan }}</p>
                    <p class="text-xs text-dark/50 truncate">{{ $b->pricelist?->nama_pricelist }} • {{ $b->tanggal_acara->format('d M Y') }} {{ $b->waktu_acara }}</p>
                </div>
                <x-status-badge :status="$b->status_booking" />
            </a>
            @empty
            <p class="p-4 text-sm text-dark/40 text-center">Tidak ada jadwal mendatang.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
