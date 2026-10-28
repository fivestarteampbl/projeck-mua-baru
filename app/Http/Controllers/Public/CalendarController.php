<?php

namespace App\Http\Controllers\Public;

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
        return view('public.kalender.index');
    }

    public function data(Request $request): JsonResponse
    {
        $awal = Carbon::create($request->integer('year', now()->year), $request->integer('month', now()->month), 1);
        $akhir = $awal->copy()->endOfMonth();

        // Publik hanya boleh tahu jadwal yang terisi, bukan detail pelanggan.
        $events = Booking::menempatiJadwal()
            ->whereBetween('tanggal_acara', [$awal->toDateString(), $akhir->toDateString()])
            ->get(['tanggal_acara', 'waktu_acara'])
            ->map(fn (Booking $b) => [
                'tanggal_acara' => $b->tanggal_acara->toDateString(),
                'waktu_acara' => $b->waktu_acara,
            ]);

        return response()->json(['events' => $events]);
    }
}
