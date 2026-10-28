<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_booking' => ['required', Rule::enum(BookingStatus::class)],
        ];
    }

    public function status(): BookingStatus
    {
        return BookingStatus::from($this->validated('status_booking'));
    }
}
