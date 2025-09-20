<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'seller.se@yopmail.com'],
            [
                'name' => 'SE Seller',
                'password' => bcrypt('password'),
            ]
        )->assignRole('seller');
    }
}
