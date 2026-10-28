<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Exceptions\BookingTidakDapatDibuatException;
use App\Mail\BookingDibuat;
use App\Models\Booking;
use App\Models\Notifikasi;
use App\Models\Pricelist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingService
{
    public function buat(array $data): Booking
    {
        $booking = DB::transaction(function () use ($data) {
            $pricelist = Pricelist::with('layanan')->findOrFail($data['pricelist_id']);

            $this->pastikanPaketTersedia($pricelist);
            $this->pastikanJadwalKosong($data['tanggal_acara'], $data['waktu_acara']);

            $booking = Booking::create([
                ...$data,
                'status_booking' => BookingStatus::Pending,
                'total_harga' => $pricelist->harga,
                'dp' => $pricelist->hitungDp(),
            ]);

            Notifikasi::create([
                'pesan' => "{$booking->nama_pelanggan} membuat booking {$pricelist->nama_pricelist} ({$booking->kode_booking}).",
            ]);

            return $booking;
        });

        $this->kirimEmailKonfirmasi($booking);

        return $booking;
    }

    private function kirimEmailKonfirmasi(Booking $booking): void
    {
        try {
            $booking->load('pricelist.layanan');
            Mail::to($booking->email)->send(new BookingDibuat($booking));
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email booking: '.$e->getMessage());
        }
    }

    private function pastikanPaketTersedia(Pricelist $pricelist): void
    {
        if (! $pricelist->isTersedia()) {
            throw BookingTidakDapatDibuatException::paketTidakTersedia();
        }
    }

    private function pastikanJadwalKosong(string $tanggal, string $waktu): void
    {
        $bentrok = Booking::menempatiJadwal()
            ->padaJadwal($tanggal, $waktu)
            ->exists();

        if ($bentrok) {
            throw BookingTidakDapatDibuatException::jadwalBentrok();
        }
    }
}
