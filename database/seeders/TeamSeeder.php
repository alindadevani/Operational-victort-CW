<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Mengisi data awal Team sesuai kebutuhan PT Victory International Futures.
     */
    public function run(): void
    {
        $teams = [
            'Team Harry',
            'Team Ari',
            'Team Ino',
            'Team Marketing',
        ];

        foreach ($teams as $namaTeam) {
            Team::create([
                'nama_team' => $namaTeam,
            ]);
        }
    }
}
