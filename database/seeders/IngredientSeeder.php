<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::create([
            'quantity' => 2,
            'description' => 'Cups of flour',
        ]);

        Ingredient::create([
            'quantity' => 1,
            'description' => 'Teaspoon of salt',
        ]);
    }
}
