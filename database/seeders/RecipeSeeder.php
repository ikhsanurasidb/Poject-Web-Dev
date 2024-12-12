<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::create([
            'image_url' => 'https://example.com/recipe1.jpg',
            'name' => 'Recipe One',
            'duration' => 30,
            'rating' => 5,
        ]);

        Recipe::create([
            'image_url' => 'https://example.com/recipe2.jpg',
            'name' => 'Recipe Two',
            'duration' => 45,
            'rating' => 4,
        ]);
    }
}
