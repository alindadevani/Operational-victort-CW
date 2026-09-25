<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengelola data team.
     * (Otorisasi ganda selain middleware role, untuk keamanan berlapis)
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        // ID team saat ini (untuk kasus update, supaya nama boleh sama dengan dirinya sendiri)
        $teamId = $this->route('team')?->id;

        return [
            'nama_team' => [
                'required',
                'string',
                'max:100',
                Rule::unique('teams', 'nama_team')->ignore($teamId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_team.required' => 'Nama team wajib diisi.',
            'nama_team.string' => 'Nama team harus berupa teks.',
            'nama_team.max' => 'Nama team maksimal 100 karakter.',
            'nama_team.unique' => 'Nama team sudah terdaftar, gunakan nama lain.',
        ];
    }
}
