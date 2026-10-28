<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class LegacyDataSeeder extends Seeder
{
    /**
     * Lanjutan migrasi data dari project lama (mua-booking) ke tabel rebuild:
     * booking, testimoni, galeri, notifikasi.
     *
     * (layanan & pricelist sudah ditangani LayananPricelistSeeder)
     */
    private string $storageLama;

    public function __construct()
    {
        // Project lama masih menyimpan file storage (galeri, testimoni, qr)
        $this->storageLama = dirname(base_path()) . '/mua-booking/storage/app/public';
    }

    public function run(): void
    {
        if (! Schema::hasTable('booking') || ! Schema::hasTable('bookings')) {
            $this->command?->warn('Tabel booking lama/baru tidak ditemukan, seeding dilewati.');

            return;
        }

        $layananMap = $this->petaLayanan();
        $pricelistMap = $this->petaPricelist($layananMap);
        $bookingMap = $this->salinBooking($pricelistMap);
        $this->salinTestimoni($bookingMap);
        $this->salinGaleri();
        $this->salinNotifikasi();
    }

    /** id_layanan lama -> id layanans baru (cocokkan lewat nama) */
    private function petaLayanan(): array
    {
        $map = [];

        foreach (DB::table('layanan')->get() as $lama) {
            $baru = DB::table('layanans')->where('nama_layanan', $lama->nama_layanan)->first();

            if ($baru) {
                $map[$lama->id_layanan] = $baru->id;
            }
        }

        return $map;
    }

    /** id_pricelist lama -> id pricelists baru (cocokkan lewat nama + layanan) */
    private function petaPricelist(array $layananMap): array
    {
        $map = [];

        foreach (DB::table('pricelist')->get() as $lama) {
            $layananId = $layananMap[$lama->id_layanan] ?? null;

            $baru = DB::table('pricelists')
                ->where('nama_pricelist', $lama->nama_pricelist)
                ->when($layananId, fn ($q) => $q->where('layanan_id', $layananId))
                ->first();

            if ($baru) {
                $map[$lama->id_pricelist] = $baru->id;
            }
        }

        return $map;
    }

    private function salinBooking(array $pricelistMap): array
    {
        if (DB::table('bookings')->exists()) {
            $this->command?->info('bookings sudah berisi, dilewati.');

            $map = [];

            foreach (DB::table('booking')->get() as $lama) {
                $id = DB::table('bookings')->where('kode_booking', $lama->kode_booking)->value('id');

                if ($id) {
                    $map[$lama->id_booking] = $id;
                }
            }

            return $map;
        }

        $map = [];

        foreach (DB::table('booking')->orderBy('id_booking')->get() as $lama) {
            if (! isset($pricelistMap[$lama->id_pricelist])) {
                $this->command?->warn("Booking {$lama->kode_booking} dilewati, pricelist {$lama->id_pricelist} tidak ditemukan.");

                continue;
            }

            $map[$lama->id_booking] = DB::table('bookings')->insertGetId([
                'pricelist_id' => $pricelistMap[$lama->id_pricelist],
                'kode_booking' => $lama->kode_booking,
                'nama_pelanggan' => $lama->nama_pelanggan,
                'no_hp' => $lama->no_hp,
                'email' => $lama->email,
                'tanggal_acara' => $lama->tanggal_acara,
                'waktu_acara' => $lama->waktu_acara,
                'lokasi_acara' => $lama->lokasi_acara,
                'catatan_tambahan' => $lama->catatan_tambahan,
                'total_harga' => (int) round((float) $lama->total_harga),
                'dp' => (int) round((float) $lama->dp),
                'status_booking' => $lama->status_booking,
                'status_pembayaran' => $lama->status_pembayaran,
                'metode_pembayaran' => $lama->metode_pembayaran,
                'payment_qr' => $this->salinFile($lama->payment_qr),
                'token_testimoni' => $lama->token_testimoni ?: null,
                'expired_testimoni' => $lama->expired_testimoni ?: null,
                'created_at' => $lama->created_at,
                'updated_at' => $lama->updated_at,
            ]);
        }

        $this->command?->info(count($map) . ' booking berhasil disalin.');

        return $map;
    }

    private function salinTestimoni(array $bookingMap): void
    {
        if (DB::table('testimonis')->exists()) {
            $this->command?->info('testimonis sudah berisi, dilewati.');

            return;
        }

        $jumlah = 0;

        foreach (DB::table('testimoni')->orderBy('id_testimoni')->get() as $lama) {
            $bookingId = $bookingMap[$lama->id_booking] ?? null;

            if (! $bookingId) {
                $this->command?->warn("Testimoni {$lama->id_testimoni} dilewati, booking asal tidak ditemukan.");

                continue;
            }

            DB::table('testimonis')->insert([
                'booking_id' => $bookingId,
                'nama_pelanggan' => $lama->nama_pelanggan,
                'foto' => $this->salinFile($lama->foto),
                'rating' => $lama->rating,
                'ulasan' => $lama->ulasan,
                'status' => $lama->status,
                'created_at' => $lama->created_at,
                'updated_at' => $lama->updated_at,
            ]);
            $jumlah++;
        }

        $this->command?->info($jumlah . ' testimoni berhasil disalin.');
    }

    private function salinGaleri(): void
    {
        if (DB::table('galeris')->exists()) {
            $this->command?->info('galeris sudah berisi, dilewati.');

            return;
        }

        $jumlah = 0;

        foreach (DB::table('galeri')->orderBy('id_galeri')->get() as $lama) {
            DB::table('galeris')->insert([
                'judul' => $lama->judul_galeri,
                'deskripsi' => $lama->deskripsi_galeri,
                'foto' => $this->salinFile($lama->foto),
                'kategori' => $lama->kategori_galeri,
                'aktif' => (bool) $lama->status_aktif,
                'urutan' => (int) $lama->urutan_galeri,
                'created_at' => $lama->created_at,
                'updated_at' => $lama->updated_at,
            ]);
            $jumlah++;
        }

        $this->command?->info($jumlah . ' galeri berhasil disalin.');
    }

    private function salinNotifikasi(): void
    {
        if (DB::table('notifikasis')->exists()) {
            $this->command?->info('notifikasis sudah berisi, dilewati.');

            return;
        }

        $rows = DB::table('notifikasi')->orderBy('id_notifikasi')->get()
            ->map(fn ($n) => [
                'pesan' => $n->pesan,
                'sudah_dibaca' => (bool) $n->status_baca,
                'created_at' => $n->created_at,
                'updated_at' => $n->updated_at,
            ])
            ->all();

        DB::table('notifikasis')->insert($rows);

        $this->command?->info(count($rows) . ' notifikasi berhasil disalin.');
    }

    /** Salin file dari storage project lama ke storage project baru. */
    private function salinFile(?string $pathLama): ?string
    {
        if ($pathLama === null || $pathLama === '') {
            return null;
        }

        $pathLama = ltrim($pathLama, '/');
        $sumber = $this->storageLama . '/' . $pathLama;
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
