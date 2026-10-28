<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGaleriRequest;
use App\Http\Requests\UpdateGaleriRequest;
use App\Models\Galeri;
use App\Services\FotoStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        return view('admin.galeri.index', ['galeris' => Galeri::berurutan()->get()]);
    }

    public function store(StoreGaleriRequest $request, FotoStorage $foto): RedirectResponse
    {
        Galeri::create([
            ...$request->safe()->except('foto'),
            'aktif' => $request->boolean('aktif'),
            'foto' => $foto->simpan($request->file('foto'), 'galeri'),
            'urutan' => (Galeri::max('urutan') ?? 0) + 1,
        ]);

        return back()->with('success', 'Foto berhasil ditambahkan.');
    }

    public function update(UpdateGaleriRequest $request, Galeri $galeri, FotoStorage $foto): RedirectResponse
    {
        $data = [
            ...$request->safe()->except('foto'),
            'aktif' => $request->boolean('aktif'),
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $foto->ganti($galeri->foto, $request->file('foto'), 'galeri');
        }

        $galeri->update($data);

        return back()->with('success', 'Foto berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri, FotoStorage $foto): RedirectResponse
    {
        $foto->hapus($galeri->foto);
        $galeri->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
