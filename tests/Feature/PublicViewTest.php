<?php

// tests/Feature/PublicViewTest.php

use App\Enums\BookingStatus;
use App\Services\BookingStatusService;

test('halaman beranda menampilkan layanan aktif', function () {
    $paket = buatPaket();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($paket->layanan->nama_layanan);
});

test('halaman detail paket menampilkan harga dan tombol pesan', function () {
    $paket = buatPaket();

    $this->get(route('pricelist.show', $paket))
        ->assertOk()
        ->assertSee($paket->nama_pricelist)
        ->assertSee(route('booking.create', ['pricelist' => $paket->id]), false);
});

test('form booking terisi otomatis saat paket dipilih dari query', function () {
    $paket = buatPaket();

    $this->get(route('booking.create', ['pricelist' => $paket->id]))
        ->assertOk()
        ->assertSee('selected', false);
});

test('halaman sukses menampilkan kode booking dan dp', function () {
    $booking = buatBooking();

    $this->get(route('booking.sukses', $booking->kode_booking))
        ->assertOk()
        ->assertSee($booking->kode_booking);
});

test('halaman cek booking tidak error walau belum ada kode dicari', function () {
    $this->get(route('booking.cek'))->assertOk();
});

test('halaman testimoni menolak token kadaluarsa dengan 403', function () {
    $booking = buatBooking();
    $service = app(BookingStatusService::class);

    $service->ubah($booking, BookingStatus::Konfirmasi);
    $service->ubah($booking, BookingStatus::Selesai);

    $booking->refresh()->update(['expired_testimoni' => now()->subHour()]);

    $this->get(route('testimoni.create', $booking->token_testimoni))->assertForbidden();
});
