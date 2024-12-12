<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Direction;

class DirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Direction::create([
            'image_url' => 'https://example.com/direction1.jpg',
            'description' => 'First step description',
        ]);

        Direction::create([
            'image_url' => 'https://example.com/direction2.jpg',
            'description' => 'Second step description',
        ]);
    }
}
