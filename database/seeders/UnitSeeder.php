<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        Unit::create([
            'code' => 'Yayasan',
            'name' => 'Yayasan Prasama Bhakti',
            'slug' => 'yayasan'
        ]);

        Unit::create([
            'code' => 'TBTK',
            'name' => 'TB-TK Santa Ursula Bandung',
            'slug' => 'tbtk'
        ]);

        Unit::create([
            'code' => 'SD',
            'name' => 'SD Santa Ursula Bandung',
            'slug' => 'sd'
        ]);

        Unit::create([
            'code' => 'SMP',
            'name' => 'SMP Santa Ursula Bandung',
            'slug' => 'smp'
        ]);
    }
}