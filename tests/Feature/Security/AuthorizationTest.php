<?php

// tests/Feature/Security/AuthorizationTest.php

use App\Models\Layanan;
use App\Models\User;
use Illuminate\Routing\Route as RouteClass;
use Illuminate\Support\Facades\Route;

test('tamu tidak bisa membuka panel admin dan diarahkan ke login', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('user biasa mendapat 403 saat membuka panel admin', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admin tetap bisa membuka panel admin', function () {
    $this->actingAs(buatAdmin())
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('user biasa tidak bisa mengubah status booking', function () {
    $booking = buatBooking();
    $sebelum = $booking->status_booking;

    $this->actingAs(User::factory()->create())
        ->from(route('admin.booking.index'))
        ->patch(route('admin.booking.status', $booking), ['status_booking' => 'dikonfirmasi'])
        ->assertForbidden();

    expect($booking->fresh()->status_booking)->toBe($sebelum);
});

test('user biasa tidak bisa menghapus booking', function () {
    $booking = buatBooking();

    $this->actingAs(User::factory()->create())
        ->from(route('admin.booking.index'))
        ->delete(route('admin.booking.destroy', $booking))
        ->assertForbidden();

    expect($booking->fresh())->not->toBeNull();
});

test('user biasa tidak bisa membuat kategori layanan', function () {
    $this->actingAs(User::factory()->create())
        ->from(route('admin.layanan-kategori.index'))
        ->post(route('admin.layanan-kategori.store'), [
            'nama_layanan' => 'Wedding',
            'aktif' => true,
        ])
        ->assertForbidden();

    expect(Layanan::where('nama_layanan', 'Wedding')->exists())->toBeFalse();
});

test('setiap route admin dijaga middleware admin', function () {
    $tanpaMiddleware = collect(Route::getRoutes()->getRoutes())
        ->filter(fn (RouteClass $route) => str_starts_with($route->uri(), 'admin/'))
        ->reject(fn (RouteClass $route) => in_array('admin', $route->gatherMiddleware(), true))
        ->map(fn (RouteClass $route) => $route->uri())
        ->values();

    expect($tanpaMiddleware)->toBeEmpty();
});

test('user biasa tidak bisa menaikkan dirinya jadi admin lewat form profile', function () {
    $pelanggan = User::factory()->create();

    $this->actingAs($pelanggan)
        ->patch(route('profile.update'), [
            'name' => 'Pelanggan',
            'email' => $pelanggan->email,
            'role' => 'admin',
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHasNoErrors();

    expect($pelanggan->fresh()->isAdmin())->toBeFalse();
});
