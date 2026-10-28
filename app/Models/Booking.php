<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Booking extends Model
{
    //
    public const PREFIX_KODE = 'MUA-';

    public const PANJANG_KODE = 5;

    public const DURASI_TESTIMONI_JAM = 24;

    protected $fillable = [
        'pricelist_id',
        'nama_pelanggan',
        'no_hp',
        'email',
        'tanggal_acara',
        'waktu_acara',
        'lokasi_acara',
        'catatan_tambahan',
        'total_harga',
        'dp',
        'status_booking',
        'status_pembayaran',
        'metode_pembayaran',
        'payment_qr',
        'token_testimoni',
        'expired_testimoni',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_acara' => 'date',
            'total_harga' => 'integer',
            'dp' => 'integer',
            'status_booking' => BookingStatus::class,
            'status_pembayaran' => PaymentStatus::class,
            'expired_testimoni' => 'datetime',
        ];
    }

    private static function buatKodeUnik(): string
    {
        do {
            $kode = self::PREFIX_KODE.Str::upper(Str::random(self::PANJANG_KODE));
        } while (self::where('kode_booking', $kode)->exists());

        return $kode;
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            $booking->kode_booking = self::buatKodeUnik();
        });
    }

    private static function formatWaktu(string $waktu): string
    {
        return Carbon::parse($waktu)->format('H:i');
    }

    // Wildcard LIKE dihapus supaya input pencarian tidak bisa melebar jadi full table scan.
    private static function bersihkanWildcard(string $kata): string
    {
        return str_replace(['%', '_'], '', $kata);
    }

    public function waktuAcara(): Attribute
    {
        return Attribute::set(fn (string $value) => self::formatWaktu($value));
    }

    public function pricelist(): BelongsTo
    {
        return $this->belongsTo(Pricelist::class);
    }

    public function testimoni(): HasOne
    {
        return $this->hasOne(Testimoni::class);
    }

    public function bukaTestimoni(): void
    {
        if ($this->token_testimoni !== null) {
            return;
        }

        $this->update([
            'token_testimoni' => Str::random(40),
            'expired_testimoni' => now()->addHours(self::DURASI_TESTIMONI_JAM),
        ]);
    }

    public function sudahTestimoni(): bool
    {
        return $this->testimoni()->exists();
    }

    public function tokenTestimoniValid(): bool
    {
        return $this->token_testimoni !== null
            && $this->expired_testimoni?->isFuture()
            && ! $this->sudahTestimoni();
    }

    public function scopeMenempatiJadwal(Builder $query): Builder
    {
        return $query->whereIn('status_booking', BookingStatus::menempatiJadwal());
    }

    public function scopePadaJadwal(Builder $query, string $tanggal, string $waktu): Builder
    {
        return $query
            ->whereDate('tanggal_acara', $tanggal)
            ->where('waktu_acara', self::formatWaktu($waktu));
    }

    public function scopeCari(Builder $query, string $kata): Builder
    {
        $kata = self::bersihkanWildcard($kata);

        return $query->when($kata !== '', fn (Builder $q) => $q->where(
            fn (Builder $q) => $q
                ->where('nama_pelanggan', 'like', "%{$kata}%")
                ->orWhere('no_hp', 'like', "%{$kata}%")
                ->orWhere('email', 'like', "%{$kata}%")
                ->orWhere('kode_booking', 'like', "%{$kata}%")
        ));
    }

    public function scopeDenganStatus(Builder $query, ?BookingStatus $status): Builder
    {
        return $query->when($status, fn (Builder $q) => $q->where('status_booking', $status));
    }
}
