<?php

// tests/Feature/Layout/LayoutTest.php

use App\Enums\BookingStatus;

test('admin layout menampilkan nama admin yang login', function () {
    $admin = buatAdmin(['name' => 'Admin Uji']);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Admin Uji');
});

test('status badge menampilkan label dan warna sesuai enum', function () {
    $html = (string) $this->blade('<x-status-badge :status="$s" />', ['s' => BookingStatus::Selesai]);

    expect($html)->toContain('selesai')->toContain('bg-emerald-50');
});

test('tamu diarahkan ke login saat akses layout admin', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});
