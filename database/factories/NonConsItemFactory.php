<?php

namespace Database\Factories;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class NonConsItemFactory extends Factory
{
    public function definition()
    {
        $faker = Faker::create('id_ID');
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
            'non_cons_sub_category_id' => $faker->numberBetween(1, 154),
            'brand_id' => $faker->numberBetween(1, 5),
            'shop_id' => $faker->numberBetween(1, 4),
            'fund_id' => $faker->numberBetween(1, 3),
            'room_id' => $room,
            'non_cons_condition_id' => $faker->numberBetween(1, 3),
            'unit_id' => $unit,
            'item_code' => Str::random(10),
            'item_number' => $faker->randomElement(['001', '002', '003', '004', '005']),
            'name' => ucwords($faker->unique()->words(2, true)),
            'price' => $faker->numberBetween(1000000, 10000000),
            'purchase_date' => Carbon::parse($faker->dateTimeBetween('-1 month', 'now'))->format('Y-m-d'),
            'include' => $faker->sentence(),
            'image' => 'default-item.png',
            'receipt' => 'default-receipt.pdf',
            'description' => $faker->sentence(),
            'availability' => true
        ];
    }
}