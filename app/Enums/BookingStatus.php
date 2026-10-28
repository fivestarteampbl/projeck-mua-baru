<?php

namespace App\Enums;

enum BookingStatus: string
{
    //
    case Pending = 'pending';
    case Konfirmasi = 'konfirmasi';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'pending',
            self::Konfirmasi => 'dikonfirmasi',
            self::Selesai => 'selesai',
            self::Dibatalkan => 'dibatalkan',
            self::Ditolak => 'ditolak',
        };
    }

    public static function menempatiJadwal(): array
    {
        return [self::Pending, self::Konfirmasi];
    }

    public function statusBerikutnya(): array
    {
        return match ($this) {
            self::Pending => [self::Konfirmasi, self::Ditolak, self::Dibatalkan],
            self::Konfirmasi => [self::Selesai, self::Dibatalkan],
            self::Selesai,
            self::Dibatalkan,
            self::Ditolak => []
        };
    }

    public function bisaBerubahKe(self $tujuan): bool
    {
        return in_array($tujuan, $this->statusBerikutnya(), true);
    }
}
