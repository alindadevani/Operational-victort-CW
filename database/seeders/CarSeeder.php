<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Mengisi data awal mobil operasional. Semua mobil awalnya "tersedia".
     */
    public function run(): void
    {
        $cars = [
            ['nama_mobil' => 'Toyota Avanza', 'plat_nomor' => 'L 1234 ABC'],
            ['nama_mobil' => 'Toyota Innova', 'plat_nomor' => 'L 5678 DEF'],
            ['nama_mobil' => 'Daihatsu Xenia', 'plat_nomor' => 'L 9012 GHI'],
        ];

        foreach ($cars as $car) {
            Car::create([
                'nama_mobil' => $car['nama_mobil'],
                'plat_nomor' => $car['plat_nomor'],
                'status' => 'tersedia',
                'keterangan' => null,
            ]);
        }
    }
}
