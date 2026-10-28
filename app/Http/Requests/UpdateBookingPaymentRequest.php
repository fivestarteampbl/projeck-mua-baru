<?php

namespace App\Http\Requests;

use App\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_pembayaran' => ['required', Rule::enum(PaymentStatus::class)],
            'metode_pembayaran' => ['nullable', 'string', 'max:50'],
            'payment_qr' => ['nullable', 'image', 'mimes:jpg,png', 'max:2048'],
        ];
    }
}
