<?php

namespace App\Http\Requests;

use App\Models\Borrowing;
use App\Support\BookingSlot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'team_id' => ['required', 'exists:teams,id'],
            'lokasi' => ['required', 'string', 'max:150'],
            'keperluan' => ['required', 'string', 'max:500'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'slot_mulai' => ['required', Rule::in(collect(BookingSlot::daftar())->pluck('mulai')->all())],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'team_id.required' => 'Team wajib dipilih.',
            'team_id.exists' => 'Team tidak valid.',
            'lokasi.required' => 'Lokasi tujuan wajib diisi.',
            'keperluan.required' => 'Keperluan wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal booking tidak boleh tanggal yang sudah lewat.',
            'slot_mulai.required' => 'Slot waktu wajib dipilih.',
            'slot_mulai.in' => 'Slot waktu tidak valid.',
        ];
    }

    /** Memastikan kuota slot belum penuh saat form dikirim. */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled('tanggal') && $this->filled('slot_mulai')) {
                $jumlahTerpakai = Borrowing::jumlahBookingDiSlot($this->tanggal, $this->slot_mulai);

                if ($jumlahTerpakai >= BookingSlot::KUOTA_PER_SLOT) {
                    $validator->errors()->add(
                        'slot_mulai',
                        'Slot waktu ini sudah penuh untuk tanggal tersebut, silakan pilih slot lain.'
                    );
                }
            }
        });
    }
}
