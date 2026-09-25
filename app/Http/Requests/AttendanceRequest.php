<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    /**
     * Form ini bisa diakses siapa saja (tanpa login), karena diakses
     * lewat scan QR Code oleh karyawan menggunakan HP masing-masing.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'team_id' => ['required', 'exists:teams,id'],
            'foto' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.max' => 'Nama lengkap maksimal 100 karakter.',
            'team_id.required' => 'Team wajib dipilih.',
            'team_id.exists' => 'Team yang dipilih tidak valid.',
            'foto.required' => 'Foto wajib diambil sebelum absensi bisa disimpan.',
        ];
    }
}
