<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Exceptions\TransisiStatusTidakValidException;
use App\Models\Booking;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class BookingStatusService
{
    public function ubah(Booking $booking, BookingStatus $tujuan): void
    {
        $asal = $booking->status_booking;

        if (! $asal->bisaBerubahKe($tujuan)) {
            throw TransisiStatusTidakValidException::dari($asal, $tujuan);
        }

        DB::transaction(function () use ($booking, $tujuan) {
            $booking->update(['status_booking' => $tujuan]);

            if ($tujuan === BookingStatus::Selesai) {
                $booking->bukaTestimoni();
            }

            Notifikasi::create([
                'pesan' => $this->pesanNotifikasi($booking, $tujuan),
            ]);
        });
    }

    private function pesanNotifikasi(Booking $booking, BookingStatus $tujuan): string
    {
        $kode = $booking->kode_booking;
        $nama = $booking->nama_pelanggan;

        return match ($tujuan) {
            BookingStatus::Konfirmasi => "Booking {$kode} telah dikonfirmasi. Silakan lakukan pembayaran DP.",
            BookingStatus::Selesai => "Booking {$kode} ({$nama}) telah selesai.",
            BookingStatus::Dibatalkan => "Booking {$kode} ({$nama}) dibatalkan.",
            BookingStatus::Ditolak => "Booking {$kode} ({$nama}) ditolak.",
            default => "Booking {$kode} ({$nama}) berstatus \"{$tujuan->label()}\".",
        };
    }
}
