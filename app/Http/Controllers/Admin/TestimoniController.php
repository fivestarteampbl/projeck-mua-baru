<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTestimoniStatusRequest;
use App\Models\Testimoni;
use App\Services\FotoStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestimoniController extends Controller
{
    public function index(): View
    {
        $testimonis = Testimoni::with('booking.pricelist.layanan')->latest()->paginate(15);

        return view('admin.testimoni.index', compact('testimonis'));
    }

    public function updateStatus(UpdateTestimoniStatusRequest $request, Testimoni $testimoni): RedirectResponse
    {
        $testimoni->update(['status' => $request->status()]);

        return back()->with('success', 'Status testimoni berhasil diubah.');
    }

    public function destroy(Testimoni $testimoni, FotoStorage $foto): RedirectResponse
    {
        $foto->hapus($testimoni->foto);
        $testimoni->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
