<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan semua seeder aplikasi.
     * Jalankan dengan: php artisan db:seed
     */
    public function run(): void
    {
        $this->call([
            TeamSeeder::class,
            CarSeeder::class,
            UserSeeder::class,
        ]);
    }
}
