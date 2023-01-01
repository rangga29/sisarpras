<?php

namespace Database\Seeders;

use App\Models\Consumer;
use Illuminate\Database\Seeder;

class ConsumerSeeder extends Seeder
{
    public function run()
    {
        Consumer::create([
            'name' => 'Jordan Pickford',
            'slug' => 'jordan-pickford',
            'position_id' => 1,
            'unit_id' => 1
        ]);

        Consumer::create([
            'name' => 'Luke Shaw',
            'slug' => 'luke-shaw',
            'position_id' => 2,
            'unit_id' => 1
        ]);

        Consumer::create([
            'name' => 'Harry Maguire',
            'slug' => 'harry-maguire',
            'position_id' => 3,
            'unit_id' => 1
        ]);

        Consumer::create([
            'name' => 'John Stones',
            'slug' => 'john-stones',
            'position_id' => 2,
            'unit_id' => 2
        ]);

        Consumer::create([
            'name' => 'Kieran Trippier',
            'slug' => 'kieran-trippier',
            'position_id' => 3,
            'unit_id' => 2
        ]);

        Consumer::create([
            'name' => 'Declan Rice',
            'slug' => 'declan-rice',
            'position_id' => 4,
            'unit_id' => 2
        ]);

        Consumer::create([
            'name' => 'Jude Bellingham',
            'slug' => 'jude-bellingham',
            'position_id' => 3,
            'unit_id' => 3
        ]);

        Consumer::create([
            'name' => 'Jordan Henderson',
            'slug' => 'jordan-henderson',
            'position_id' => 4,
            'unit_id' => 3
        ]);

        Consumer::create([
            'name' => 'Mason Mount',
            'slug' => 'mason-mount',
            'position_id' => 5,
            'unit_id' => 3
        ]);

        Consumer::create([
            'name' => 'Raheem Sterling',
            'slug' => 'raheem-sterling',
            'position_id' => 4,
            'unit_id' => 4
        ]);

        Consumer::create([
            'name' => 'Bukayo Saka',
            'slug' => 'bukayo-saka',
            'position_id' => 5,
            'unit_id' => 4
        ]);

        Consumer::create([
            'name' => 'Harry Kane',
            'slug' => 'harry-kane',
            'position_id' => 6,
            'unit_id' => 4
        ]);
    }
}