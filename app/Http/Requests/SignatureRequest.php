<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignatureRequest extends FormRequest
{
    /** User yang login boleh mengisi tanda tangan melalui controller pemilik booking. */
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'nama_sopir' => ['required', 'string', 'max:100'],
            'tanda_tangan_sopir' => ['required', 'string'],
            'tanda_tangan_peminjam' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_sopir.required' => 'Nama sopir wajib diisi.',
            'tanda_tangan_sopir.required' => 'Tanda tangan sopir wajib diisi.',
            'tanda_tangan_peminjam.required' => 'Tanda tangan peminjam wajib diisi.',
        ];
    }
}