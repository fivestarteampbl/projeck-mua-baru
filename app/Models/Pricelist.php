<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pricelist extends Model
{
    //
    public const PERSEN_DP = 30;

    protected $fillable = [
        'layanan_id',
        'nama_pricelist',
        'deskripsi',
        'harga',
        'foto_pricelist',
        'isi_paket',
        'syarat_ketentuan',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'isi_paket' => 'array',
            'syarat_ketentuan' => 'array',
        ];
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function hitungDp(): int
    {
        return (int) round($this->harga * self::PERSEN_DP / 100);
    }

    public function isTersedia(): bool
    {
        return (bool) $this->layanan?->aktif;
    }
}
