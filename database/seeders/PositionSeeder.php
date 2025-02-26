<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Position::query()->insert([
            [
                'name' => 'Lawyer',
            ],
            [
                'name' => 'Content manager',
            ],
            [
                'name' => 'Security',
            ],
            [
                'name' => 'Designer',
            ],
        ]);
    }
}
