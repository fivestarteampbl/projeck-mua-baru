<?php

namespace App\Exceptions;

use App\Enums\BookingStatus;
use DomainException;

class TransisiStatusTidakValidException extends DomainException
{
    public static function dari(BookingStatus $asal, BookingStatus $tujuan): self
    {
        return new self(
            "Status \"{$asal->label()}\" tidak dapat diubah menjadi \"{$tujuan->label()}\"."
        );
    }
}
