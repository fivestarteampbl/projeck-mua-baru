<?php

// tests/Feature/Booking/AdminBookingTest.php

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Notifikasi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = buatAdmin();
});

test('tamu tidak bisa mengubah status booking', function () {
    $booking = buatBooking();

    $this->patch(route('admin.booking.status', $booking), ['status_booking' => 'konfirmasi'])
        ->assertRedirect(route('login'));
});

test('admin bisa mengonfirmasi booking pending', function () {
    $booking = buatBooking();
    $jumlahNotifikasi = Notifikasi::count();

    $this->actingAs($this->admin)
        ->patch(route('admin.booking.status', $booking), ['status_booking' => 'konfirmasi'])
        ->assertSessionHasNoErrors();

    expect($booking->refresh()->status_booking)->toBe(BookingStatus::Konfirmasi)
        ->and(Notifikasi::count())->toBe($jumlahNotifikasi + 1);
});

test('status selesai membuka link testimoni selama 24 jam', function () {
    $booking = buatBooking();
    $booking->update(['status_booking' => BookingStatus::Konfirmasi]);

    $this->actingAs($this->admin)
        ->patch(route('admin.booking.status', $booking), ['status_booking' => 'selesai']);

    $booking->refresh();

    expect($booking->token_testimoni)->toHaveLength(40)
        ->and($booking->expired_testimoni->isFuture())->toBeTrue()
        ->and($booking->expired_testimoni->lessThanOrEqualTo(now()->addHours(24)))->toBeTrue();
});

test('transisi status yang tidak valid ditolak', function () {
    $booking = buatBooking();

    $this->actingAs($this->admin)
        ->patch(route('admin.booking.status', $booking), ['status_booking' => 'selesai'])
        ->assertSessionHasErrors('status_booking');

    expect($booking->refresh()->status_booking)->toBe(BookingStatus::Pending);
});

test('admin bisa memperbarui pembayaran dan mengunggah qr', function () {
    Storage::fake('public');
    $booking = buatBooking();

    $this->actingAs($this->admin)
        ->patch(route('admin.booking.pembayaran', $booking), [
            'status_pembayaran' => 'dp',
            'metode_pembayaran' => 'QRIS',
            'payment_qr' => UploadedFile::fake()->image('qr.png'),
        ])
        ->assertSessionHasNoErrors();

    $booking->refresh();

    expect($booking->status_pembayaran)->toBe(PaymentStatus::Dp);
    Storage::disk('public')->assertExists($booking->payment_qr);
});

test('menghapus booking ikut menghapus file qr', function () {
    Storage::fake('public');
    $booking = buatBooking();

    $this->actingAs($this->admin)->patch(route('admin.booking.pembayaran', $booking), [
        'status_pembayaran' => 'dp',
        'payment_qr' => UploadedFile::fake()->image('qr.png'),
    ]);
    $path = $booking->refresh()->payment_qr;

    $this->actingAs($this->admin)->delete(route('admin.booking.destroy', $booking));

    Storage::disk('public')->assertMissing($path);
    $this->assertModelMissing($booking);
});
