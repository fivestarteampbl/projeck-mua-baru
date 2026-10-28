<?php

use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pricelist;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function buatPaket(array $layanan = [], array $paket = []): Pricelist
{
    $layanan = Layanan::create(array_merge(
        ['nama_layanan' => 'Wedding', 'aktif' => true],
        $layanan,
    ));

    return $layanan->pricelists()->create(array_merge(
        ['nama_pricelist' => 'Paket A', 'deskripsi' => 'Uji', 'harga' => 1500000],
        $paket,
    ));
}

function dataBooking(Pricelist $paket, array $override = []): array
{
    return array_merge([
        'pricelist_id' => $paket->id,
        'nama_pelanggan' => 'Budi',
        'no_hp' => '081234567890',
        'email' => 'budi@example.com',
        'tanggal_acara' => now()->addDays(7)->toDateString(),
        'waktu_acara' => '09:00',
        'lokasi_acara' => 'Banyuwangi',
    ], $override);
}

function buatBooking(array $override = []): Booking
{
    $paket = buatPaket();

    return app(BookingService::class)->buat(dataBooking($paket, $override));
}

function buatAdmin(array $override = []): User
{
    return User::factory()->admin()->create($override);
}
