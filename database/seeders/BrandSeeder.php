<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        Brand::create([
            'name' => 'Lenovo',
            'slug' => 'lenovo',
            'image' => 'default-brand.png'
        ]);

        Brand::create([
            'name' => 'Asus ROG',
            'slug' => 'asus-rog',
            'image' => 'default-brand.png'
        ]);

        Brand::create([
            'name' => 'Epson',
            'slug' => 'epson',
            'image' => 'default-brand.png'
        ]);

        Brand::create([
            'name' => 'Telkom',
            'slug' => 'telkom',
            'image' => 'default-brand.png'
        ]);

        Brand::create([
            'name' => 'Snowman',
            'slug' => 'snowman',
            'image' => 'default-brand.png'
        ]);
    }
}