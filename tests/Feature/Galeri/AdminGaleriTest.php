<?php

use App\Models\Galeri;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin bisa upload foto galeri dan urutan otomatis bertambah', function () {
    Storage::fake('public');
    $admin = buatAdmin();

    Galeri::create(['judul' => 'A', 'deskripsi' => 'x', 'foto' => 'x.jpg', 'kategori' => 'Wedding', 'urutan' => 1]);

    $this->actingAs($admin)->post(route('admin.galeri.store'), [
        'judul' => 'B', 'deskripsi' => 'y', 'kategori' => 'Wisuda',
        'foto' => UploadedFile::fake()->image('b.jpg'),
    ])->assertSessionHasNoErrors();

    expect(Galeri::where('judul', 'B')->firstOrFail()->urutan)->toBe(2);
});
