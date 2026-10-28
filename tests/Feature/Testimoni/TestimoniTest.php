<?php

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Notifikasi;
use App\Services\BookingStatusService;

function bookingSiapTestimoni(): Booking
{
    $booking = buatBooking();
    $service = app(BookingStatusService::class);
    $service->ubah($booking, BookingStatus::Konfirmasi);
    $service->ubah($booking, BookingStatus::Selesai);

    return $booking->refresh();
}

test('testimoni bisa dikirim dengan token yang valid', function () {
    $booking = bookingSiapTestimoni();

    $this->post(route('testimoni.store', $booking->token_testimoni), [
        'rating' => 5,
        'ulasan' => 'Sangat memuaskan',
    ])->assertRedirect(route('home'));

    expect($booking->sudahTestimoni())->toBeTrue();
});

test('testimoni yang masuk membuat notifikasi admin', function () {
    $booking = bookingSiapTestimoni();
    $jumlahNotifikasi = Notifikasi::count();

    $this->post(route('testimoni.store', $booking->token_testimoni), [
        'rating' => 5,
        'ulasan' => 'Sangat memuaskan',
    ]);

    expect(Notifikasi::count())->toBe($jumlahNotifikasi + 1);
});

test('testimoni kedua dengan token sama ditolak', function () {
    $booking = bookingSiapTestimoni();

    $this->post(route('testimoni.store', $booking->token_testimoni), ['rating' => 5, 'ulasan' => 'Bagus']);

    $this->post(route('testimoni.store', $booking->token_testimoni), ['rating' => 4, 'ulasan' => 'Lagi'])
        ->assertForbidden();
});

test('token testimoni yang kadaluarsa ditolak', function () {
    $booking = bookingSiapTestimoni();

    $booking->update(['expired_testimoni' => now()->subHour()]);

    $this->get(route('testimoni.create', $booking->token_testimoni))->assertForbidden();
});
