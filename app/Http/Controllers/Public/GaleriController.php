<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        return view('public.galeri', ['galeris' => Galeri::aktif()->berurutan()->get()]);
    }
}
