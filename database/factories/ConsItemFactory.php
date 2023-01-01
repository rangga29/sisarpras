<?php

namespace Database\Factories;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsItemFactory extends Factory
{
    public function definition()
    {
        $faker = Faker::create('id_ID');
        $name = ucwords($faker->unique()->words(2, true));
        $initial_amount = $faker->numberBetween(10, 50);
        $stock_amount = $initial_amount;
        $unit = $faker->numberBetween(1, 4);
        if ($unit == 1) {
            $room = $faker->numberBetween(1, 2);
        } elseif ($unit == 2) {
            $room = $faker->numberBetween(3, 4);
        } elseif ($unit == 3) {
            $room = $faker->numberBetween(5, 6);
        } else {
            $room = $faker->numberBetween(7, 8);
        }

        return [
            'cons_sub_category_id' => $faker->numberBetween(1, 6),
            'brand_id' => $faker->numberBetween(1, 5),
            'shop_id' => $faker->numberBetween(1, 4),
            'fund_id' => $faker->numberBetween(1, 3),
            'room_id' => $room,
            'unit_id' => $unit,
            'item_code' => Str::random(10),
            'name' => $name,
            'initial_amount' => $initial_amount,
            'taken_amount' => 0,
            'stock_amount' => $stock_amount,
            'price' => $faker->numberBetween(1000000, 10000000),
            'purchase_date' => Carbon::parse($faker->dateTimeBetween('-1 month', 'now'))->format('Y-m-d'),
            'image' => 'default-item.png',
            'receipt' => 'default-receipt.pdf',
            'description' => $faker->sentence(),
        ];
    }
}