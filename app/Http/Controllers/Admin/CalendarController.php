<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        return view('admin.kalender.index');
    }

    public function data(Request $request): JsonResponse
    {
        [$awal, $akhir] = $this->rentangBulan($request);

        $bookings = Booking::with('pricelist.layanan')
            ->where('status_booking', BookingStatus::Konfirmasi)
            ->whereBetween('tanggal_acara', [$awal, $akhir])
            ->orderBy('tanggal_acara')
            ->orderBy('waktu_acara')
            ->get()
            ->map(fn (Booking $b) => [
                'id' => $b->id,
                'kode_booking' => $b->kode_booking,
                'tanggal_acara' => $b->tanggal_acara->toDateString(),
                'waktu_acara' => $b->waktu_acara,
                'nama_pelanggan' => $b->nama_pelanggan,
                'no_hp' => $b->no_hp,
                'email' => $b->email,
                'lokasi_acara' => $b->lokasi_acara,
                'catatan_tambahan' => $b->catatan_tambahan ?? '',
                'layanan' => $b->pricelist?->layanan?->nama_layanan ?? '-',
                'nama_pricelist' => $b->pricelist?->nama_pricelist,
                'status_booking' => $b->status_booking->value,
                'manage_url' => route('admin.booking.show', $b),
            ]);

        return response()->json(['events' => $bookings]);
    }

    /** @return array{0: string, 1: string} */
    private function rentangBulan(Request $request): array
    {
        $awal = Carbon::create($request->integer('year', now()->year), $request->integer('month', now()->month), 1);

        return [$awal->toDateString(), $awal->copy()->endOfMonth()->toDateString()];
    }
}
