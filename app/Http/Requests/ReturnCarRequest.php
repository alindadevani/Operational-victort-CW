<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnCarRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh memproses pengembalian mobil.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}
