<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $carId = $this->route('car')?->id;

        return [
            'nama_mobil' => ['required', 'string', 'max:100'],
            'plat_nomor' => [
                'required',
                'string',
                'max:20',
                Rule::unique('cars', 'plat_nomor')->ignore($carId),
            ],
            'status' => ['required', Rule::in(['tersedia', 'dipinjam'])],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_mobil.required' => 'Nama mobil wajib diisi.',
            'plat_nomor.required' => 'Plat nomor wajib diisi.',
            'plat_nomor.unique' => 'Plat nomor sudah terdaftar.',
            'status.required' => 'Status mobil wajib dipilih.',
            'status.in' => 'Status mobil tidak valid.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 5 MB.',
        ];
    }
}
