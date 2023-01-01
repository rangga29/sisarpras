<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        Room::create([
            'name' => 'Ruang Yayasan 1',
            'slug' => 'ruang-yayasan-1',
            'unit_id' => 1
        ]);

        Room::create([
            'name' => 'Ruang Yayasan 2',
            'slug' => 'ruang-yayasan-2',
            'unit_id' => 1
        ]);

        Room::create([
            'name' => 'Kelas TK A',
            'slug' => 'kelas-tk-a',
            'unit_id' => 2
        ]);

        Room::create([
            'name' => 'Kelas TK B',
            'slug' => 'kelas-tk-b',
            'unit_id' => 2
        ]);

        Room::create([
            'name' => 'Bangsal',
            'slug' => 'bangsal',
            'unit_id' => 3
        ]);

        Room::create([
            'name' => 'Aula Atas',
            'slug' => 'aula-atas',
            'unit_id' => 3
        ]);

        Room::create([
            'name' => 'Biancosi',
            'slug' => 'biancosi',
            'unit_id' => 4
        ]);

        Room::create([
            'name' => 'Cremona',
            'slug' => 'cremona',
            'unit_id' => 4
        ]);
    }
}