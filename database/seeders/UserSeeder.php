<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'admin',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'name' => 'Super Administrator',
            'unit_id' => 1,
            'role' => 1
        ]);

        User::create([
            'username' => 'sub_admin',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'name' => 'Sub Administrator',
            'unit_id' => 1,
            'role' => 2
        ]);

        User::create([
            'username' => 'admin_tbtk',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'name' => 'Administrator TBTK',
            'unit_id' => 2,
            'role' => 1
        ]);

        User::create([
            'username' => 'sub_admin_tbtk',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'name' => 'Sub Administrator TBTK',
            'unit_id' => 2,
            'role' => 2
        ]);

        User::create([
            'username' => 'admin_sd',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'name' => 'Administrator SD',
            'unit_id' => 3,
            'role' => 1
        ]);

        User::create([
            'username' => 'sub_admin_sd',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'name' => 'Sub Administrator SD',
            'unit_id' => 3,
            'role' => 2
        ]);

        User::create([
            'username' => 'admin_smp',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'name' => 'Administrator SMP',
            'unit_id' => 4,
            'role' => 1
        ]);

        User::create([
            'username' => 'sub_admin_smp',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'name' => 'Sub Administrator SMP',
            'unit_id' => 4,
            'role' => 2
        ]);
    }
}