<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bersihkan tabel lama (project sebelum rebuild) yang sudah dimigrasi
     * penuh ke tabel baru: layanans, pricelists, bookings, testimonis,
     * galeris, notifikasis.
     */
    public function up(): void
    {
        // Urut sesuai dependency FK: child dulu, lalu parent
        foreach ([
            'detail_layanan',
            'testimoni',
            'booking',
            'pricelist',
            'layanan',
            'galeri',
            'notifikasi',
            'personal_access_tokens', // Sanctum tidak dipakai rebuild ini
        ] as $tabel) {
            if (Schema::hasTable($tabel)) {
                Schema::drop($tabel);
            }
        }

        // Hapus jejak migrasi project lama supaya `migrate:status` bersih
        DB::table('migrations')->whereIn('migration', [
            '2026_05_16_000000_create_layanan_table',
            '2026_05_16_111111_create_pricelist_table',
            '2026_05_16_111112_create_booking_table',
            '2026_05_16_163915_create_detail_layanan_table',
            '2026_05_16_164128_create_galeri_table',
            '2026_05_16_164138_create_notifikasi_table',
            '2026_05_16_213802_create_testimoni_table',
            '2026_06_01_202021_add_detail_fields_to_pricelist_table',
            '2026_06_03_144214_add_testimoni_fields_to_booking_table',
            '2026_06_08_000001_add_payment_fields_to_booking_table',
            '2026_06_09_172638_add_foto_to_testimoni_table',
            '2026_08_14_085045_create_personal_access_tokens_table',
        ])->delete();
    }

    public function down(): void
    {
        // Tidak bisa dikembalikan — struktur & data tabel lama sudah tidak relevan
    }
};
