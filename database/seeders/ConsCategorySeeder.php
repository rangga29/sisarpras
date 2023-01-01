<?php

namespace Database\Seeders;

use App\Models\ConsCategory;
use Illuminate\Database\Seeder;

class ConsCategorySeeder extends Seeder
{
    public function run()
    {
        ConsCategory::create([
            'category_name' => 'Alat Tulis',
            'category_slug' => 'alat-tulis',
        ]);

        ConsCategory::create([
            'category_name' => 'Tinta Printer',
            'category_slug' => 'tinta-printer',
        ]);

        ConsCategory::create([
            'category_name' => 'Komputer',
            'category_slug' => 'komputer',
        ]);
    }
}