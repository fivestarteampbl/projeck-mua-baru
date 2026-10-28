<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pricelist_id' => ['required', 'integer', 'exists:pricelists,id'],
            'nama_pelanggan' => ['required', 'string', 'max:50'],
            'no_hp' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:100'],
            'tanggal_acara' => ['required', 'date_format:Y-m-d', 'after:today'],
            'waktu_acara' => ['required', 'date_format:H:i'],
            'lokasi_acara' => ['required', 'string', 'max:100'],
            'catatan_tambahan' => ['nullable', 'string', 'max:255'],
        ];
    }
}
