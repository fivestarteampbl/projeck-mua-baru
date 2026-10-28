<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\CekBookingRequest;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CekBookingController extends Controller
{
    public function cek(): View
    {
        return view('public.booking.cek');
    }

    public function hasil(CekBookingRequest $request): View|JsonResponse|RedirectResponse
    {
        $booking = Booking::with('pricelist.layanan')
            ->where('kode_booking', $request->validated('kode_booking'))
            ->first();

        if ($booking === null) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Booking tidak ditemukan.'], 404)
                : back()->withErrors(['kode_booking' => 'Booking dengan kode tersebut tidak ditemukan.']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status_booking' => $booking->status_booking->value,
                'status_pembayaran' => $booking->status_pembayaran->value,
                'has_qr' => $booking->payment_qr !== null,
                'has_testimoni_token' => $booking->tokenTestimoniValid(),
            ]);
        }

        return view('public.booking.hasil', compact('booking'));
    }
}
