<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(): View
    {
        return view('admin.notifikasi.index', ['notifikasis' => Notifikasi::latest()->paginate(50)]);
    }

    public function tandaiDibaca(Notifikasi $notifikasi): RedirectResponse
    {
        $notifikasi->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function tandaiSemuaDibaca(): RedirectResponse
    {
        Notifikasi::belumDibaca()->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroy(Notifikasi $notifikasi): RedirectResponse
    {
        $notifikasi->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
