<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimoniRequest;
use App\Models\Booking;
use App\Models\Notifikasi;
use App\Services\FotoStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestimoniController extends Controller
{
    public function create(string $token): View
    {
        $booking = Booking::where('token_testimoni', $token)->firstOrFail();

        abort_unless($booking->tokenTestimoniValid(), 403, 'Link testimoni tidak berlaku atau sudah kadaluarsa.');

        return view('public.testimoni.create', compact('booking'));
    }

    public function store(StoreTestimoniRequest $request, string $token, FotoStorage $foto): RedirectResponse
    {
        $booking = Booking::where('token_testimoni', $token)->firstOrFail();

        abort_unless($booking->tokenTestimoniValid(), 403, 'Link testimoni tidak berlaku atau sudah kadaluarsa.');

        $data = [
            'booking_id' => $booking->id,
            'nama_pelanggan' => $booking->nama_pelanggan,
            ...$request->safe()->except('foto'),
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $foto->simpan($request->file('foto'), 'testimoni');
        }

        $booking->testimoni()->create($data);

        Notifikasi::create([
            'pesan' => "{$booking->nama_pelanggan} mengirim testimoni untuk booking {$booking->kode_booking}.",
        ]);

        return redirect()->route('home')->with('success', 'Terima kasih atas testimoni Anda.');
    }
}
