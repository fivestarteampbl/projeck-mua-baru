<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'total' => Booking::count(),
            'hariIni' => Booking::whereDate('tanggal_acara', today())->count(),
            'pending' => Booking::where('status_booking', BookingStatus::Pending)->count(),
            'selesai' => Booking::where('status_booking', BookingStatus::Selesai)->count(),
            'pendapatanBulanIni' => (int) Booking::where('status_booking', BookingStatus::Selesai)
                ->whereMonth('tanggal_acara', now()->month)
                ->sum('total_harga'),
            'bookingTerbaru' => Booking::with('pricelist')->latest()->take(5)->get(),
            'bookingMendatang' => Booking::with('pricelist')
                ->menempatiJadwal()
                ->whereDate('tanggal_acara', '>=', today())
                ->orderBy('tanggal_acara')
                ->take(5)
                ->get(),
        ]);
    }
}
