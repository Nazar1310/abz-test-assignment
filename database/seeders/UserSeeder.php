<?php

namespace Database\Seeders;

use App\Models\Position;
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
        $positions = Position::query()->get();
        $faker = Factory::create();
        $users = [];
        for ($i = 0; $i < 45; $i++) {
            $email = $i == 0 ? 'test@gmail.com' : $faker->unique()->safeEmail;
            $operatorCodes = ['96', '97', '98', '63'];
            $phone = '+380' . $faker->randomElement($operatorCodes) . $faker->randomNumber(7, true);
            $users[] =  [
                'name' => $faker->name,
                'email' => $email,
                'phone' => $phone,
                'position_id' => $positions->random()->id,
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
