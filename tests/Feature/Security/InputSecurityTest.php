<?php

// tests/Feature/Security/InputSecurityTest.php

use App\Models\Booking;
use App\Models\Galeri;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('nama pelanggan berisi script selalu di-escape di halaman admin', function () {
    Storage::fake('public');
    $admin = buatAdmin();
    buatBooking(['nama_pelanggan' => '<script>alert(1)</script>']);

    $this->actingAs($admin)
        ->get(route('admin.booking.index'))
        ->assertOk()
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
});

test('pencarian booking aman dari sql injection', function () {
    $admin = buatAdmin();
    buatBooking(['nama_pelanggan' => 'Budi Santoso']);

    $this->actingAs($admin)
        ->get(route('admin.booking.index', ['search' => "'; DROP TABLE bookings; --"]))
        ->assertOk()
        ->assertDontSee('Budi Santoso');

    expect(Booking::count())->toBe(1);
});

test('wildcard like dianggap karakter biasa bukan pola', function () {
    $admin = buatAdmin();
    buatBooking(['nama_pelanggan' => 'Budi Santoso']);

    $this->actingAs($admin)
        ->get(route('admin.booking.index', ['search' => 'B%di']))
        ->assertOk()
        ->assertDontSee('Budi Santoso')
        ->assertSee('Belum ada booking.');
});

test('cek booking dengan kode tidak dikenal tidak membocorkan data', function () {
    $this->from(route('booking.cek'))
        ->get(route('booking.hasil', ['kode_booking' => 'MUA-XXXXX']))
        ->assertRedirect(route('booking.cek'))
        ->assertSessionHasErrors('kode_booking');
});

test('cek booking json untuk kode tidak dikenal membalas 404', function () {
    $this->getJson(route('booking.hasil', ['kode_booking' => 'MUA-XXXXX']))
        ->assertNotFound()
        ->assertJsonMissingPath('nama_pelanggan');
});

test('cek booking dibatasi rate limit', function () {
    $booking = buatBooking();
    $url = route('booking.hasil', ['kode_booking' => $booking->kode_booking]);

    foreach (range(1, 10) as $ignored) {
        $this->get($url)->assertOk();
    }

    $this->get($url)->assertStatus(429);
});

test('upload file skrip ditolak', function () {
    Storage::fake('public');
    $admin = buatAdmin();

    $this->actingAs($admin)
        ->post(route('admin.galeri.store'), [
            'judul' => 'Shell',
            'deskripsi' => 'x',
            'kategori' => 'Wedding',
            'foto' => UploadedFile::fake()->create('shell.php', 10, 'application/x-php'),
        ])
        ->assertSessionHasErrors('foto');

    expect(Galeri::where('judul', 'Shell')->exists())->toBeFalse();
});

test('upload svg ditolak', function () {
    Storage::fake('public');
    $admin = buatAdmin();

    $this->actingAs($admin)
        ->post(route('admin.galeri.store'), [
            'judul' => 'Vektor',
            'deskripsi' => 'x',
            'kategori' => 'Wedding',
            'foto' => UploadedFile::fake()->create('vektor.svg', 10, 'image/svg+xml'),
        ])
        ->assertSessionHasErrors('foto');

    expect(Galeri::where('judul', 'Vektor')->exists())->toBeFalse();
});

test('hapus paket yang terpakai booking memberi pesan error bukan 500', function () {
    Storage::fake('public');
    $paket = buatPaket();
    buatBooking(['pricelist_id' => $paket->id]);

    $this->actingAs(buatAdmin())
        ->from(route('admin.layanan.index'))
        ->delete(route('admin.layanan.destroy', $paket))
        ->assertRedirect(route('admin.layanan.index'))
        ->assertSessionHasErrors('pricelist');

    expect($paket->fresh())->not->toBeNull();
});

test('laporan mengabaikan bulan dan tahun di luar rentang', function () {
    $this->actingAs(buatAdmin())
        ->get(route('admin.laporan.index', ['bulan' => 99, 'tahun' => 9999]))
        ->assertOk();
});
