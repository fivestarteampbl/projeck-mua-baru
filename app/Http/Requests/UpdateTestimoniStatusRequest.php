<?php

// app/Http/Requests/UpdateTestimoniStatusRequest.php

namespace App\Http\Requests;

use App\Enums\TestimoniStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTestimoniStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['status' => ['required', Rule::enum(TestimoniStatus::class)]];
    }

    public function status(): TestimoniStatus
    {
        return TestimoniStatus::from($this->validated('status'));
    }
}
