<?php

use App\Models\Notifikasi;

test('tandai semua dibaca mengubah semua notifikasi belum dibaca', function () {
    $admin = buatAdmin();
    Notifikasi::factory()->count(3)->create(['sudah_dibaca' => false]);

    $this->actingAs($admin)->post(route('admin.notifikasi.bacaSemua'));

    expect(Notifikasi::belumDibaca()->count())->toBe(0);
});
