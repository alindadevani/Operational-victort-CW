<?php

namespace App\Support;

/**
 * Kelas ini menyimpan daftar slot waktu tetap untuk booking mobil operasional.
 */
class BookingSlot
{
    /** Kuota maksimal booking untuk setiap slot waktu. */
    public const KUOTA_PER_SLOT = 2;

    /** Mengambil daftar slot waktu yang tersedia setiap hari. */
    public static function daftar(): array
    {
        return [
            ['mulai' => '08:00', 'selesai' => '10:00', 'label' => '08:00 - 10:00'],
            ['mulai' => '10:00', 'selesai' => '12:00', 'label' => '10:00 - 12:00'],
            ['mulai' => '12:00', 'selesai' => '14:00', 'label' => '12:00 - 14:00'],
            ['mulai' => '14:00', 'selesai' => '15:00', 'label' => '14:00 - 15:00'],
            ['mulai' => '15:00', 'selesai' => '17:00', 'label' => '15:00 - 17:00'],
        ];
    }
}