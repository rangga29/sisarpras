<?php

namespace Database\Seeders;

use App\Models\Fund;
use Illuminate\Database\Seeder;

class FundSeeder extends Seeder
{
    public function run()
    {
        Fund::create([
            'name' => 'Dana Bos',
            'slug' => 'dana-bos'
        ]);

        Fund::create([
            'name' => 'Yayasan',
            'slug' => 'yayasan'
        ]);

        Fund::create([
            'name' => 'Kas',
            'slug' => 'kas'
        ]);
    }
}