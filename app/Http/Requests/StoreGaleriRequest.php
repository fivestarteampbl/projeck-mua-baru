<?php

// app/Http/Requests/StoreGaleriRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGaleriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:50'],
            'deskripsi' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:50'],
            'foto' => [$this->isMethod('post') ? 'required' : 'nullable', 'image', 'mimes:jpg,png', 'max:4096'],
            'aktif' => ['boolean'],
        ];
    }
}
