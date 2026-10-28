<?php

namespace App\Exceptions;

use DomainException;

class BookingTidakDapatDibuatException extends DomainException
{
    private function __construct(string $message, public readonly string $field)
    {
        parent::__construct($message);
    }

    public static function paketTidakTersedia(): self
    {
        return new self('Paket ini sudah tidak tersedia.', 'pricelist_id');
    }

    public static function jadwalBentrok(): self
    {
        return new self('Jadwal tersebut sudah terisi. Silakan pilih tanggal atau waktu lainnya.', 'waktu_acara');
    }
}
