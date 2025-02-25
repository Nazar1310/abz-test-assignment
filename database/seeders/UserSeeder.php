<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Factory::create();
        $users = [];
        for ($i = 0; $i < 45; $i++) {
            $users[] =  [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('qwerty123'), // For testing
                'email_verified_at' => now(),
                'photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        User::query()->insert($users);
    }
}
