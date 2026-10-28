<?php

use App\Models\Layanan;
use App\Models\Pricelist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(fn () => $this->admin = buatAdmin());

test('admin bisa membuat paket dengan isi paket multi baris', function () {
    $layanan = Layanan::create(['nama_layanan' => 'Wedding', 'aktif' => true]);

    $this->actingAs($this->admin)->post(route('admin.layanan.store'), [
        'layanan_id' => $layanan->id,
        'nama_pricelist' => 'Paket Gold',
        'deskripsi' => 'Deskripsi',
        'harga' => 2000000,
        'isi_paket' => "Make up\nHair do\n\nSoftlens",
    ])->assertSessionHasNoErrors();

    expect(Pricelist::firstOrFail()->isi_paket)->toBe(['Make up', 'Hair do', 'Softlens']);
});

test('foto paket tersimpan ke kolom foto_pricelist', function () {
    Storage::fake('public');
    $layanan = Layanan::create(['nama_layanan' => 'Wedding', 'aktif' => true]);

    $this->actingAs($this->admin)->post(route('admin.layanan.store'), [
        'layanan_id' => $layanan->id,
        'nama_pricelist' => 'Paket Foto',
        'deskripsi' => 'Deskripsi',
        'harga' => 1000000,
        'foto' => UploadedFile::fake()->image('paket.jpg'),
    ])->assertSessionHasNoErrors();

    $pricelist = Pricelist::firstOrFail();

    expect($pricelist->foto_pricelist)->not->toBeNull();
    Storage::disk('public')->assertExists($pricelist->foto_pricelist);
});

test('foto kategori layanan tersimpan ke kolom foto_layanan', function () {
    Storage::fake('public');

    $this->actingAs($this->admin)->post(route('admin.layanan-kategori.store'), [
        'nama_layanan' => 'Wisuda',
        'aktif' => true,
        'foto' => UploadedFile::fake()->image('kategori.jpg'),
    ])->assertSessionHasNoErrors();

    $layanan = Layanan::firstOrFail();

    expect($layanan->foto_layanan)->not->toBeNull();
    Storage::disk('public')->assertExists($layanan->foto_layanan);
});

test('menghapus paket yang masih punya booking ditolak dengan pesan error', function () {
    $paket = buatPaket();
    buatBooking(['pricelist_id' => $paket->id]);

    $this->actingAs($this->admin)
        ->from(route('admin.layanan.index'))
        ->delete(route('admin.layanan.destroy', $paket))
        ->assertRedirect(route('admin.layanan.index'))
        ->assertSessionHasErrors('pricelist');

    expect($paket->fresh())->not->toBeNull();
});
