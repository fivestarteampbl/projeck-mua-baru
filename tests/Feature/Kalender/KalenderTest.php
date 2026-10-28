<?php

test('data kalender publik tidak membocorkan nama pelanggan', function () {
    buatBooking();

    $response = $this->getJson(route('kalender.data', ['year' => now()->year, 'month' => now()->month]));

    $response->assertOk();
    expect($response->json('events.0'))->not->toHaveKey('nama_pelanggan');
});

test('halaman kalender publik terbuka untuk tamu tanpa data pribadi', function () {
    $this->get(route('kalender.index'))
        ->assertOk()
        ->assertSee('Kalender Ketersediaan')
        ->assertDontSee('Kelola booking');
});

test('halaman kalender admin terbuka untuk admin', function () {
    $this->actingAs(buatAdmin())
        ->get(route('admin.kalender.index'))
        ->assertOk()
        ->assertSee('Kalender Booking')
        ->assertSee('Kelola booking');
});
