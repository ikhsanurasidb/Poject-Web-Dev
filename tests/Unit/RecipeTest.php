<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Direction;

class RecipeTest extends TestCase
{

    public function test_can_create_recipe()
    {
        $recipeData = [
            'image_url' => 'https://example.com/150',
            'name' => 'Gado-Gado Special',
            'description' => 'Delicious Indonesian salad with peanut sauce.',
            'servings' => 4,
            'duration' => 30,
            'rating' => 5,
            'created_by' => 1,
            'created_by_name' => 'Theofilus',
        ];

        $recipe = Recipe::create($recipeData);

        $this->assertDatabaseHas('recipes', [
            'image_url' => 'https://example.com/150',
            'name' => 'Gado-Gado Special',
            'description' => 'Delicious Indonesian salad with peanut sauce.',
        ]);
    }

    public function test_can_create_recipe_with_ingredients_and_directions()
    {
        $recipe = Recipe::factory()->create([
            'image_url' => 'https://example.com/150',
            'name' => 'Gado-Gado Special',
            'description' => 'Delicious Indonesian salad with peanut sauce.',
            'servings' => 4,
            'duration' => 30,
            'rating' => 5,
            'created_by' => 1,
            'created_by_name' => 'Theofilus',
        ]);

        $recipe->ingredients()->createMany([
            ['description' => 'Kacang Panjang', 'quantity' => '50', 'unit' => 'g'],
            ['description' => 'Tahu', 'quantity' => '100', 'unit'=> 'g'],
            ['description' => 'Saus Kacang', 'quantity' => '100', 'unit' => 'ml'],
        ]);

        $recipe->directions()->createMany([
            ['image_url' => 'https://example.com/201', 'description' => 'Kukus semua sayur.'],
            ['image_url' => 'https://example.com/202', 'description' => 'Tuangkan saus kacang.'],
        ]);

        $this->assertEquals(3, $recipe->ingredients()->count());
        $this->assertEquals(2, $recipe->directions()->count());

        $this->assertDatabaseHas('ingredients', ['description' => 'Kacang Panjang']);
        $this->assertDatabaseHas('directions', ['description' => 'Kukus semua sayur.']);
    }
}