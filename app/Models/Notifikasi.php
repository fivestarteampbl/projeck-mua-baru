<?php

namespace App\Models;

use Database\Factories\NotifikasiFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    //
    /** @use HasFactory<NotifikasiFactory> */
    use HasFactory;

    protected $fillable = [
        'pesan',
        'sudah_dibaca',
    ];

    protected function casts(): array
    {
        return ['sudah_dibaca' => 'boolean'];
    }

    public function scopeBelumDibaca(Builder $query): Builder
    {
        return $query->where('sudah_dibaca', false);
    }
}
