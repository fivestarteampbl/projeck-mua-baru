<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\FotoStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::with('pricelist.layanan')
            ->denganStatus($request->enum('status', BookingStatus::class))
            ->cari($request->string('search')->trim()->toString())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.booking.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load('pricelist.layanan');

        return view('admin.booking.show', compact('booking'));
    }

    public function destroy(Booking $booking, FotoStorage $foto): RedirectResponse
    {
        $foto->hapus($booking->payment_qr);
        $booking->delete();

        return redirect()
            ->route('admin.booking.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}
