<?php

namespace Database\Seeders;

use App\Models\NonConsCondition;
use Illuminate\Database\Seeder;

class NonConsConditionSeeder extends Seeder
{
    public function run()
    {
        NonConsCondition::create([
            'name' => 'Normal',
            'slug' => 'normal',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        NonConsCondition::create([
            'name' => 'Rusak',
            'slug' => 'rusak',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        NonConsCondition::create([
            'name' => 'Hibah',
            'slug' => 'hibah',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        NonConsCondition::create([
            'name' => 'Dihapus',
            'slug' => 'dihapus',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}