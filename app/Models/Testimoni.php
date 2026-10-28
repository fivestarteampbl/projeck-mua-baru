<?php

namespace App\Models;

use App\Enums\TestimoniStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimoni extends Model
{
    //
    protected $fillable = [
        'booking_id',
        'nama_pelanggan',
        'foto',
        'rating',
        'ulasan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'status' => TestimoniStatus::class,
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeDitampilkan(Builder $query): Builder
    {
        return $query->where('status', TestimoniStatus::Ditampilkan);
    }
}
