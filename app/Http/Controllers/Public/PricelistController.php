<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pricelist;
use Illuminate\View\View;

class PricelistController extends Controller
{
    public function show(Pricelist $pricelist): View
    {
        return view('public.pricelist.show', [
            'pricelist' => $pricelist->load('layanan'),
        ]);
    }
}
