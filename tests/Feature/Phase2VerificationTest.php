<?php

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pricelist;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('phase 2 data structure and behavior verification', function () {
    // 1. Buat Layanan & Pricelist
    $layanan = Layanan::create(['nama_layanan' => 'Wedding', 'aktif' => true]);

    $pricelist = $layanan->pricelists()->create([
        'nama_pricelist' => 'Paket A',
        'deskripsi' => 'Uji',
        'harga' => 1500000,
        'isi_paket' => ['Make up', 'Hair do'],
    ]);

    // Verifikasi Hitung DP, Availability, & Array Casting
    expect($pricelist->hitungDp())->toBe(450000)
        ->and($pricelist->isTersedia())->toBeTrue()
        ->and($pricelist->isi_paket)->toBe(['Make up', 'Hair do']);

    // 2. Buat Booking
    $booking = $pricelist->bookings()->create([
        'kode_booking' => 'MUA-TEST1',
        'nama_pelanggan' => 'Budi',
        'no_hp' => '081234567890',
        'email' => 'budi@example.com',
        'tanggal_acara' => '2026-12-01',
        'waktu_acara' => '09:00',
        'lokasi_acara' => 'Banyuwangi',
        'total_harga' => 1500000,
        'dp' => 450000,
    ]);

    // Verifikasi Casting Enum & Relasi berantai
    expect($booking->refresh()->status_booking)->toBe(BookingStatus::Pending)
        ->and($booking->pricelist->layanan->nama_layanan)->toBe('Wedding')
        ->and(Booking::menempatiJadwal()->count())->toBe(1);

    // Verifikasi Scope menempatiJadwal setelah status Batal
    $booking->update(['status_booking' => BookingStatus::Dibatalkan]);
    expect(Booking::menempatiJadwal()->count())->toBe(0);

    // Verifikasi Availability saat Layanan non-aktif
    $layanan->update(['aktif' => false]);
    expect($pricelist->refresh()->isTersedia())->toBeFalse();

    // Verifikasi Protect restrictOnDelete saat pricelist dihapus
    expect(fn () => $pricelist->delete())->toThrow(QueryException::class);
});
