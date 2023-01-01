<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run()
    {
        Shop::create([
            'name' => 'Persada Komputer',
            'slug' => 'persada-komputer',
            'image' => 'default-shop.png'
        ]);

        Shop::create([
            'name' => 'Warung Pak Imran',
            'slug' => 'warung-pak-imran',
            'image' => 'default-shop.png'
        ]);

        Shop::create([
            'name' => 'PT John Doe',
            'slug' => 'pt-john-doe',
            'image' => 'default-shop.png'
        ]);

        Shop::create([
            'name' => 'Toko Besi ABC',
            'slug' => 'toko-besi-abc',
            'image' => 'default-shop.png'
        ]);
    }
}