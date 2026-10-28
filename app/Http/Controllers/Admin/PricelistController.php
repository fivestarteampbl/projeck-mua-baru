<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePricelistRequest;
use App\Http\Requests\UpdatePricelistRequest;
use App\Models\Layanan;
use App\Models\Pricelist;
use App\Services\FotoStorage;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PricelistController extends Controller
{
    public function index(): View
    {
        $pricelists = Pricelist::with('layanan')->latest()->get()->groupBy('layanan.nama_layanan');
        $layanans = Layanan::aktif()->get();

        return view('admin.layanan.index', compact('pricelists', 'layanans'));
    }

    public function store(StorePricelistRequest $request, FotoStorage $foto): RedirectResponse
    {
        $data = $request->dataTersimpan();

        if ($request->hasFile('foto')) {
            $data['foto_pricelist'] = $foto->simpan($request->file('foto'), 'pricelist');
        }

        Pricelist::create($data);

        return back()->with('success', 'Paket berhasil ditambahkan.');
    }

    public function update(UpdatePricelistRequest $request, Pricelist $pricelist, FotoStorage $foto): RedirectResponse
    {
        $data = $request->dataTersimpan();

        if ($request->hasFile('foto')) {
            $data['foto_pricelist'] = $foto->ganti($pricelist->foto_pricelist, $request->file('foto'), 'pricelist');
        }

        $pricelist->update($data);

        return back()->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Pricelist $pricelist, FotoStorage $foto): RedirectResponse
    {
        try {
            $pricelist->delete();
        } catch (QueryException) {
            return back()->withErrors([
                'pricelist' => 'Paket tidak bisa dihapus karena masih dipakai booking.',
            ]);
        }

        $foto->hapus($pricelist->foto_pricelist);

        return back()->with('success', 'Paket berhasil dihapus.');
    }
}
