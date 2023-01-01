<?php

namespace Database\Seeders;

use App\Models\ConsSubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsSubCategorySeeder extends Seeder
{
    public function run()
    {
        ConsSubCategory::create([
            'cons_category_id' => '1',
            'sub_category_name' => 'Pensil',
            'sub_category_slug' => 'pensil'
        ]);

        ConsSubCategory::create([
            'cons_category_id' => '1',
            'sub_category_name' => 'Pulpen',
            'sub_category_slug' => 'pulpen'
        ]);

        ConsSubCategory::create([
            'cons_category_id' => '2',
            'sub_category_name' => 'Tinta Warna',
            'sub_category_slug' => 'tinta-warna'
        ]);

        ConsSubCategory::create([
            'cons_category_id' => '2',
            'sub_category_name' => 'Tinta Hitam',
            'sub_category_slug' => 'tinta-hitam'
        ]);

        ConsSubCategory::create([
            'cons_category_id' => '3',
            'sub_category_name' => 'Kabel Internet',
            'sub_category_slug' => 'kabel-internet'
        ]);

        ConsSubCategory::create([
            'cons_category_id' => '3',
            'sub_category_name' => 'Kepala RJ45',
            'sub_category_slug' => 'kepala-rj45'
        ]);
    }
}