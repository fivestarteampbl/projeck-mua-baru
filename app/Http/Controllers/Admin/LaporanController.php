<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LaporanService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request, LaporanService $laporan): View
    {
        $bulan = max(1, min(12, $request->integer('bulan', now()->month)));
        $tahun = max(2000, min(2100, $request->integer('tahun', now()->year)));

        return view('admin.laporan.index', [
            ...$laporan->bulanan($bulan, $tahun),
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }
}
