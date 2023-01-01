<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run()
    {
        Position::create([
            'name' => 'Kepala Sekolah',
            'slug' => 'kepala-sekolah'
        ]);

        Position::create([
            'name' => 'Guru',
            'slug' => 'guru'
        ]);

        Position::create([
            'name' => 'Kependidikan',
            'slug' => 'kependidikan'
        ]);

        Position::create([
            'name' => 'Penunjang',
            'slug' => 'penunjang'
        ]);

        Position::create([
            'name' => 'Satpam',
            'slug' => 'satpam'
        ]);

        Position::create([
            'name' => 'Siswa',
            'slug' => 'siswa'
        ]);

        Position::create([
            'name' => 'Lain-Lain',
            'slug' => 'lain-lain'
        ]);
    }
}