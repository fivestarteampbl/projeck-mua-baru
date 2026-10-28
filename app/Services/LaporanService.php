<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LaporanService
{
    public function bulanan(int $bulan, int $tahun): array
    {
        $query = fn (): Builder => Booking::query()
            ->whereYear('tanggal_acara', $tahun)
            ->whereMonth('tanggal_acara', $bulan);

        $perStatus = [];
        foreach (BookingStatus::cases() as $status) {
            $perStatus[$status->value] = (clone $query())->where('status_booking', $status)->count();
        }

        return [
            'total' => $query()->count(),
            'pendapatan' => (int) (clone $query())->where('status_booking', BookingStatus::Selesai)->sum('total_harga'),
            'perStatus' => $perStatus,
            'bookings' => $query()->with('pricelist.layanan')->latest('tanggal_acara')->get(),
            'perPaket' => $this->perPaket($bulan, $tahun),
        ];
    }

    private function perPaket(int $bulan, int $tahun): Collection
    {
        return Booking::with('pricelist')
            ->whereYear('tanggal_acara', $tahun)
            ->whereMonth('tanggal_acara', $bulan)
            ->where('status_booking', BookingStatus::Selesai)
            ->selectRaw('pricelist_id, count(*) as total, sum(total_harga) as omset')
            ->groupBy('pricelist_id')
            ->get();
    }
}
