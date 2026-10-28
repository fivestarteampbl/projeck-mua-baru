<?php

namespace App\Enums;

enum TestimoniStatus: string
{
    //
    case Pending = 'pending';
    case Ditampilkan = 'ditampilkan';
    case Disembunyikan = 'disembunyikan';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Ditampilkan => 'Ditampilkan',
            self::Disembunyikan => 'Disembunyikan',
        };
    }
}
