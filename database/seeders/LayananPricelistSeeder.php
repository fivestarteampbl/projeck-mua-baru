<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LayananPricelistSeeder extends Seeder
{
    /**
     * Salin data layanan & pricelist dari tabel lama (`layanan`, `pricelist`)
     * ke tabel rebuild (`layanans`, `pricelists`) supaya halaman publik
     * (beranda, katalog, detail paket) menampilkan data dari database.
     */
    public function run(): void
    {
        if (DB::table('layanans')->exists() || DB::table('pricelists')->exists()) {
            $this->command?->info('Tabel layanans/pricelists sudah berisi data, seeding dilewati.');

            return;
        }

        if (! DB::getSchemaBuilder()->hasTable('layanan') || ! DB::getSchemaBuilder()->hasTable('pricelist')) {
            $this->command?->warn('Tabel lama (layanan/pricelist) tidak ditemukan, seeding dilewati.');

            return;
        }

        $idMap = [];

        foreach (DB::table('layanan')->orderBy('id_layanan')->get() as $lama) {
            $idMap[$lama->id_layanan] = DB::table('layanans')->insertGetId([
                'nama_layanan' => $lama->nama_layanan,
                'deskripsi' => $lama->deskripsi_layanan,
                'foto_layanan' => $this->salinFoto($lama->foto),
                'aktif' => (bool) $lama->aktif,
                'created_at' => $lama->created_at,
                'updated_at' => $lama->updated_at,
            ]);
        }

        $syaratStandar = [
            'Booking minimal H-1 sebelum acara',
            'DP sebesar 30% dari total harga',
            'Pelunasan dilakukan sebelum atau pada hari acara',
            'Perubahan jadwal wajib dikonfirmasi terlebih dahulu',
        ];

        foreach (DB::table('pricelist')->orderBy('id_pricelist')->get() as $lama) {
            if (! isset($idMap[$lama->id_layanan])) {
                continue;
            }

            $isi = json_decode($lama->isi_paket ?? 'null', true) ?: [];
            $syarat = json_decode($lama->syarat_ketentuan ?? 'null', true) ?: [];

            // Bersihkan data lama yang salah isi (item "isi_paket" ikut tersimpan di "syarat_ketentuan")
            $syarat = array_values(array_filter($syarat, fn ($s) => ! in_array($s, $isi, true)));

            if ($syarat === []) {
                $syarat = $syaratStandar;
            }

            DB::table('pricelists')->insert([
                'layanan_id' => $idMap[$lama->id_layanan],
                'nama_pricelist' => $lama->nama_pricelist,
                'deskripsi' => $lama->deskripsi_pricelist,
                'harga' => (int) round((float) $lama->harga_pricelist),
                'foto_pricelist' => $this->salinFoto($lama->foto_pricelist),
                'isi_paket' => json_encode(array_values($isi)),
                'syarat_ketentuan' => json_encode($syarat),
                'created_at' => $lama->created_at,
                'updated_at' => $lama->updated_at,
            ]);
        }

        $this->command?->info('Data layanan & pricelist berhasil disalin dari tabel lama.');
    }

    /**
     * Salin file gambar dari public/ ke disk public (storage/app/public)
     * supaya path yang disimpan bisa dirender oleh Storage::url().
     */
    private function salinFoto(?string $pathLama): ?string
    {
        if ($pathLama === null || $pathLama === '') {
            return null;
        }

        $pathLama = ltrim($pathLama, '/');
        $sumber = public_path($pathLama);
        $tujuan = storage_path('app/public/' . $pathLama);

        if (! File::exists($sumber)) {
            return null;
        }

        if (! File::exists($tujuan)) {
            File::ensureDirectoryExists(dirname($tujuan));
            File::copy($sumber, $tujuan);
        }

        return $pathLama;
    }
}
