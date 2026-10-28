@extends('layouts.admin')
@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Booking')

@section('content')
{{-- Filter Tabs --}}
<div class="flex gap-4 border-b border-dark/20 mb-6 text-sm font-medium overflow-x-auto whitespace-nowrap pb-1 scrollbar-hide">
    <a href="{{ route('admin.booking.index', array_merge(request()->query(), ['status' => ''])) }}"
       class="px-4 py-2 border-b-2 transition shrink-0 {{ request('status') ? 'border-transparent text-dark/60 hover:text-dark' : 'border-dark text-dark' }}">
        ALL BOOKING
    </a>
    @foreach(App\Enums\BookingStatus::cases() as $status)
    <a href="{{ route('admin.booking.index', array_merge(request()->query(), ['status' => $status->value])) }}"
       class="uppercase px-4 py-2 border-b-2 transition shrink-0 {{ request('status') === $status->value ? 'border-dark text-dark' : 'border-transparent text-dark/60 hover:text-dark' }}">
        {{ $status->label() }}
    </a>
    @endforeach
</div>

{{-- Search --}}
<form method="GET" class="mb-4">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama, kode, atau WhatsApp..."
           class="px-4 py-2 text-sm rounded-lg border border-dark/20 focus:outline-none focus:border-dark w-full max-w-sm">
</form>

{{-- Desktop Table --}}
<div class="bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[700px] text-left border-collapse">
            <thead>
                <tr class="bg-pearly text-sm border-b border-dark/10">
                    <th class="p-4 font-semibold">Customer</th>
                    <th class="p-4 font-semibold">Layanan</th>
                    <th class="p-4 font-semibold">Tanggal & Waktu</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold">Pembayaran</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($bookings as $booking)
                <tr class="border-b border-dark/5 hover:bg-pearly/50">
                    <td class="p-4">
                        <p class="font-medium">{{ $booking->nama_pelanggan }}</p>
                        <p class="text-xs text-dark/50">{{ $booking->kode_booking }}</p>
                    </td>
                    <td class="p-4">{{ $booking->pricelist?->nama_pricelist }}</td>
                    <td class="p-4">
                        {{ $booking->tanggal_acara->format('d M Y') }}<br>
                        <span class="text-xs text-dark/50">{{ $booking->waktu_acara }}</span>
                    </td>
                    <td class="p-4"><x-status-badge :status="$booking->status_booking" /></td>
                    <td class="p-4">
                        @if($booking->status_pembayaran->value === 'lunas')
                            <span class="text-xs text-green-600 font-semibold">Lunas</span>
                        @elseif($booking->status_pembayaran->value === 'dp')
                            <span class="text-xs text-blue-600 font-semibold">DP Dibayar</span>
                        @else
                            <span class="text-xs text-red-500 font-semibold">Belum DP</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <a href="{{ route('admin.booking.show', $booking) }}" class="text-xs font-semibold text-dark hover:underline mr-2">Detail</a>
                        <form action="{{ route('admin.booking.destroy', $booking) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus booking ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-dark/40 text-sm">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="block md:hidden space-y-3">
    @forelse($bookings as $booking)
    <div class="bg-white rounded-xl shadow-sm border border-dark/10 p-4">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="font-semibold text-sm">{{ $booking->nama_pelanggan }}</p>
                <p class="text-xs text-dark/50">{{ $booking->kode_booking }}</p>
            </div>
            <x-status-badge :status="$booking->status_booking" />
        </div>
        <div class="text-xs text-dark/70 space-y-1">
            <p>{{ $booking->pricelist?->nama_pricelist }}</p>
            <p>{{ $booking->tanggal_acara->format('d M Y') }} &middot; {{ $booking->waktu_acara }}</p>
            <p>Pembayaran:
                @if($booking->status_pembayaran->value === 'lunas')
                    <span class="text-green-600 font-semibold">Lunas</span>
                @elseif($booking->status_pembayaran->value === 'dp')
                    <span class="text-blue-600 font-semibold">DP Dibayar</span>
                @else
                    <span class="text-red-500 font-semibold">Belum DP</span>
                @endif
            </p>
        </div>
        <div class="flex gap-2 mt-3">
            <a href="{{ route('admin.booking.show', $booking) }}" class="flex-1 text-center text-xs font-semibold bg-pearly border border-dark/20 rounded-lg py-2">Detail</a>
            <form action="{{ route('admin.booking.destroy', $booking) }}" method="POST" class="flex-1"
                  onsubmit="return confirm('Hapus booking ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full text-xs font-semibold text-red-500 border border-red-200 rounded-lg py-2">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-dark/40 text-sm">Belum ada booking.</div>
    @endforelse
</div>

<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
