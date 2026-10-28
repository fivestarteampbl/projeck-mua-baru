<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookingPaymentRequest;
use App\Models\Booking;
use App\Services\FotoStorage;
use Illuminate\Http\RedirectResponse;

class BookingPaymentController extends Controller
{
    public function __invoke(
        UpdateBookingPaymentRequest $request,
        Booking $booking,
        FotoStorage $foto,
    ): RedirectResponse {
        $data = $request->safe()->only(['status_pembayaran', 'metode_pembayaran']);

        if ($request->hasFile('payment_qr')) {
            $data['payment_qr'] = $foto->ganti($booking->payment_qr, $request->file('payment_qr'), 'qr');
        }

        $booking->update($data);

        return back()->with('success', 'Data pembayaran berhasil diperbarui.');
    }
}
