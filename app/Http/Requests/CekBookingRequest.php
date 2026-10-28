<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CekBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'kode_booking' => strtoupper(trim((string) $this->input('kode_booking'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_booking' => ['required', 'string', 'max:10'],
        ];
    }
}
