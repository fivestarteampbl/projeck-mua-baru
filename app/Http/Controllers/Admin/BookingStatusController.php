<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\TransisiStatusTidakValidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Models\Booking;
use App\Services\BookingStatusService;
use Illuminate\Http\RedirectResponse;

class BookingStatusController extends Controller
{
    public function __invoke(
        UpdateBookingStatusRequest $request,
        Booking $booking,
        BookingStatusService $service,
    ): RedirectResponse {
        try {
            $service->ubah($booking, $request->status());
        } catch (TransisiStatusTidakValidException $e) {
            return back()->withErrors(['status_booking' => $e->getMessage()]);
        }

        return back()->with('success', 'Status booking berhasil diubah.');
    }
}
