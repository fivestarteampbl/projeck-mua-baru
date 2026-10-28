<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ParsesMultilineList;
use Illuminate\Foundation\Http\FormRequest;

class StorePricelistRequest extends FormRequest
{
    use ParsesMultilineList;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'layanan_id' => ['required', 'integer', 'exists:layanans,id'],
            'nama_pricelist' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'max:500'],
            'harga' => ['required', 'integer', 'min:0'],
            'isi_paket' => ['nullable', 'string'],
            'syarat_ketentuan' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function dataTersimpan(): array
    {
        return [
            ...$this->safe()->except(['isi_paket', 'syarat_ketentuan', 'foto']),
            'isi_paket' => $this->parseMultilineList($this->input('isi_paket')),
            'syarat_ketentuan' => $this->parseMultilineList($this->input('syarat_ketentuan')),
        ];
    }
}
