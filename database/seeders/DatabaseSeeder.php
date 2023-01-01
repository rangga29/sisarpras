<?php

namespace Database\Seeders;

use App\Models\ConsItem;
use App\Models\NonConsItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UnitSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(FundSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(ConsumerSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(ConsCategorySeeder::class);
        $this->call(ConsSubCategorySeeder::class);
        $this->call(NonConsCategorySeeder::class);
        $this->call(NonConsSubCategorySeeder::class);
        $this->call(NonConsConditionSeeder::class);
        // ConsItem::factory(100)->create();
        // NonConsItem::factory(100)->create();
    }
}