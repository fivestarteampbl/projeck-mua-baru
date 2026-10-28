<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    //
    protected $fillable = [
        'judul',
        'deskripsi',
        'foto',
        'kategori',
        'aktif',
        'urutan',

    ];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeBerurutan(Builder $query): Builder
    {
        return $query->orderBy('urutan');
    }
}
