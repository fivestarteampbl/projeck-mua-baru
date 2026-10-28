<?php

// tests/Feature/AdminViewTest.php

use App\Enums\BookingStatus;

beforeEach(fn () => $this->admin = buatAdmin());

test('dashboard menampilkan statistik', function () {
    buatBooking();

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Total Booking');
});

test('detail booking hanya menampilkan status lanjutan yang valid', function () {
    $booking = buatBooking(); // status: pending

    $this->actingAs($this->admin)
        ->get(route('admin.booking.show', $booking))
        ->assertOk()
        ->assertSee('dikonfirmasi')   // boleh: pending -> konfirmasi
        ->assertDontSee('<option value="selesai">', false); // tidak boleh: pending -> selesai
});

test('form pembayaran hanya tampil setelah dikonfirmasi', function () {
    $booking = buatBooking();

    $this->actingAs($this->admin)
        ->get(route('admin.booking.show', $booking))
        ->assertDontSee('Pembayaran DP');

    $booking->update(['status_booking' => BookingStatus::Konfirmasi]);

    $this->actingAs($this->admin)
        ->get(route('admin.booking.show', $booking))
        ->assertSee('Pembayaran DP');
});

test('halaman kategori layanan menampilkan jumlah paket', function () {
    $paket = buatPaket();

    $this->actingAs($this->admin)
        ->get(route('admin.layanan-kategori.index'))
        ->assertOk()
        ->assertSee($paket->layanan->nama_layanan)
        ->assertSee('1 paket');
});

test('laporan memfilter berdasarkan bulan dan tahun query', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.laporan.index', ['bulan' => 1, 'tahun' => 2020]))
        ->assertOk()
        ->assertSee('Tidak ada data untuk periode ini');
});
