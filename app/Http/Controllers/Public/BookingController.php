<?php

namespace App\Http\Controllers\Public;

use App\Exceptions\BookingTidakDapatDibuatException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pricelist;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookingService) {}

    public function create(Request $request): View
    {
        $layanans = Layanan::aktif()->with('pricelists')->get();

        $paketDipilih = Pricelist::with('layanan')->find($request->query('pricelist'));

        if ($paketDipilih && ! $paketDipilih->isTersedia()) {
            $paketDipilih = null;
        }

        return view('public.booking/create', compact('layanans', 'paketDipilih'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        try {
            $booking = $this->bookingService->buat($request->validated());
        } catch (BookingTidakDapatDibuatException $e) {
            return back()->withInput()->withErrors([$e->field => $e->getMessage()]);
        }

        return redirect()->route('booking.sukses', $booking->kode_booking);
    }

    public function sukses(Booking $booking): View
    {
        $booking->load('pricelist.layanan');

        return view('public.booking.sukses', compact('booking'));
    }
}
