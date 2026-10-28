<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(): View
    {
        return view('public.layanan.index', [
            'layanans' => Layanan::aktif()->with('pricelists')->get(),
        ]);
    }

    public function show(Layanan $layanan): View
    {
        abort_unless($layanan->aktif, 404);

        return view('public.layanan.show', [
            'layanan' => $layanan->load('pricelists'),
        ]);
    }
}
