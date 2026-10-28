<?php

// tests/Feature/Laporan/LaporanServiceTest.php

use App\Enums\BookingStatus;
use App\Services\LaporanService;

test('laporan bulanan menghitung pendapatan hanya dari booking selesai', function () {
    $bulanIni = now()->month;
    $tahunIni = now()->year;

    $selesai = buatBooking(['tanggal_acara' => now()->startOfMonth()->addDays(2)->toDateString()]);
    $selesai->update(['status_booking' => BookingStatus::Konfirmasi]);
    $selesai->update(['status_booking' => BookingStatus::Selesai]);

    buatBooking(['tanggal_acara' => now()->startOfMonth()->addDays(3)->toDateString()]); // masih pending

    $hasil = app(LaporanService::class)->bulanan($bulanIni, $tahunIni);

    expect($hasil['total'])->toBe(2)
        ->and($hasil['pendapatan'])->toBe($selesai->total_harga)
        ->and($hasil['perStatus']['selesai'])->toBe(1)
        ->and($hasil['perStatus']['pending'])->toBe(1);
});
