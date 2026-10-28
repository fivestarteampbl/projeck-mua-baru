<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Testimoni;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('public.home', [
            'layanans' => Layanan::aktif()->whereHas('pricelists')->with('pricelists')->get(),
            'galeris' => Galeri::aktif()->berurutan()->take(6)->get(),
            'testimonis' => Testimoni::ditampilkan()->latest()->take(5)->get(),
        ]);
    }
}
