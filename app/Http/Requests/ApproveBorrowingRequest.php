<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'car_id' => ['required', 'exists:cars,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.required' => 'Pilih mobil yang akan digunakan.',
            'car_id.exists' => 'Mobil tidak valid.',
        ];
    }
}