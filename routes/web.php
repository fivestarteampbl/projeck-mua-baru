<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\BookingPaymentController;
// ====================
// PUBLIC CONTROLLERS
// ====================
use App\Http\Controllers\Admin\BookingStatusController;
use App\Http\Controllers\Admin\CalendarController as AdminCalendarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleriController as AdminGaleriController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LayananController as AdminLayananController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\PricelistController;
// ====================
// ADMIN CONTROLLERS
// ====================
use App\Http\Controllers\Admin\TestimoniController as AdminTestimoniController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\Public\CalendarController as PublicCalendarController;
use App\Http\Controllers\Public\CekBookingController;
use App\Http\Controllers\Public\GaleriController as PublicGaleriController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LayananController as PublicLayananController;
use App\Http\Controllers\Public\PricelistController as PublicPricelistController;
use App\Http\Controllers\Public\TestimoniController as PublicTestimoniController;
use Illuminate\Support\Facades\Route;

// ======================================================
// PUBLIC
// ======================================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Layanan
Route::prefix('layanan')->name('layanan.')->group(function () {
    Route::get('/', [PublicLayananController::class, 'index'])->name('index');
    Route::get('/{layanan}', [PublicLayananController::class, 'show'])->name('show');
});

// Pricelist
Route::get('/pricelist/{pricelist}', [PublicPricelistController::class, 'show'])
    ->name('pricelist.show');

// Galeri
Route::get('/galeri', [PublicGaleriController::class, 'index'])
    ->name('galeri.index');

// Kalender
Route::prefix('kalender')->name('kalender.')->group(function () {
    Route::get('/', [PublicCalendarController::class, 'index'])->name('index');
    Route::get('/data', [PublicCalendarController::class, 'data'])->name('data');
});

// Testimoni
Route::prefix('testimoni')->name('testimoni.')->group(function () {
    Route::get('/{token}', [PublicTestimoniController::class, 'create'])
        ->name('create');

    Route::post('/{token}', [PublicTestimoniController::class, 'store'])
        ->name('store');
});

// ======================================================
// BOOKING PUBLIC
// ======================================================

Route::prefix('booking')->name('booking.')->group(function () {

    Route::get('/buat', [BookingController::class, 'create'])
        ->name('create');

    Route::post('/buat', [BookingController::class, 'store'])
        ->middleware('throttle:5,10')
        ->name('store');

    Route::get('/sukses/{booking:kode_booking}', [BookingController::class, 'sukses'])
        ->name('sukses');

    Route::get('/cek', [CekBookingController::class, 'cek'])
        ->name('cek');

    Route::get('/hasil', [CekBookingController::class, 'hasil'])
        ->middleware('throttle:10,1')
        ->name('hasil');
});

// ======================================================
// AUTHENTICATION
// ======================================================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ======================================================
// ADMIN
// ======================================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // --------------------
        // Dashboard
        // --------------------
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // --------------------
        // Booking
        // --------------------
        Route::get('/booking', [AdminBookingController::class, 'index'])
            ->name('booking.index');

        Route::get('/booking/{booking}', [AdminBookingController::class, 'show'])
            ->name('booking.show');

        Route::delete('/booking/{booking}', [AdminBookingController::class, 'destroy'])
            ->name('booking.destroy');

        Route::patch('/booking/{booking}/status', BookingStatusController::class)
            ->name('booking.status');

        Route::patch('/booking/{booking}/pembayaran', BookingPaymentController::class)
            ->name('booking.pembayaran');

        // --------------------
        // Kalender
        // --------------------
        Route::prefix('kalender')->name('kalender.')->group(function () {

            Route::get('/', [AdminCalendarController::class, 'index'])
                ->name('index');

            Route::get('/data', [AdminCalendarController::class, 'data'])
                ->name('data');
        });

        // --------------------
        // Layanan Kategori
        // --------------------
        Route::get('/layanan-kategori', [AdminLayananController::class, 'index'])
            ->name('layanan-kategori.index');

        Route::post('/layanan-kategori', [AdminLayananController::class, 'store'])
            ->name('layanan-kategori.store');

        Route::put('/layanan-kategori/{layanan}', [AdminLayananController::class, 'update'])
            ->name('layanan-kategori.update');

        // --------------------
        // Pricelist
        // --------------------
        Route::prefix('layanan')->name('layanan.')->group(function () {

            Route::get('/', [PricelistController::class, 'index'])
                ->name('index');

            Route::post('/', [PricelistController::class, 'store'])
                ->name('store');

            Route::put('/{pricelist}', [PricelistController::class, 'update'])
                ->name('update');

            Route::delete('/{pricelist}', [PricelistController::class, 'destroy'])
                ->name('destroy');
        });

        // --------------------
        // Galeri
        // --------------------
        Route::prefix('galeri')->name('galeri.')->group(function () {

            Route::get('/', [AdminGaleriController::class, 'index'])
                ->name('index');

            Route::post('/', [AdminGaleriController::class, 'store'])
                ->name('store');

            Route::put('/{galeri}', [AdminGaleriController::class, 'update'])
                ->name('update');

            Route::delete('/{galeri}', [AdminGaleriController::class, 'destroy'])
                ->name('destroy');
        });

        // --------------------
        // Testimoni
        // --------------------
        Route::prefix('testimoni')->name('testimoni.')->group(function () {

            Route::get('/', [AdminTestimoniController::class, 'index'])
                ->name('index');

            Route::patch('/{testimoni}/status', [AdminTestimoniController::class, 'updateStatus'])
                ->name('updateStatus');

            Route::delete('/{testimoni}', [AdminTestimoniController::class, 'destroy'])
                ->name('destroy');
        });

        // --------------------
        // Notifikasi
        // --------------------
        Route::prefix('notifikasi')->name('notifikasi.')->group(function () {

            Route::get('/', [NotifikasiController::class, 'index'])
                ->name('index');

            Route::patch('/{notifikasi}/baca', [NotifikasiController::class, 'tandaiDibaca'])
                ->name('baca');

            Route::post('/baca-semua', [NotifikasiController::class, 'tandaiSemuaDibaca'])
                ->name('bacaSemua');

            Route::delete('/{notifikasi}', [NotifikasiController::class, 'destroy'])
                ->name('destroy');
        });

        // --------------------
        // Laporan
        // --------------------
        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');
    });

require __DIR__.'/auth.php';
