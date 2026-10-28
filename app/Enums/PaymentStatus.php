<?php

namespace App\Enums;

enum PaymentStatus: string
{
    //
    case BelumDp = 'belum_dp';
    case Dp = 'dp';
    case Lunas = 'lunas';

    public function label(): string
    {
        return match ($this) {
            self::BelumDp => 'Belum DP',
            self::Dp => 'DP Dibayar',
            self::Lunas => 'Lunas',

        };
    }
}
