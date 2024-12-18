<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Direction;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_recipe()
    {
        $recipeData = [
            'image_url',
            'name' => 'Gado-Gado Special',
            'description' => 'Delicious Indonesian salad with peanut sauce.',
            'servings' => 4,
            'duration' => '30 minutes',
            'rating' => 5,
            'created_by' => 1,
        ];

        $recipe = Recipe::create($recipeData);

        $this->assertDatabaseHas('recipes', [
            'name' => 'Gado-Gado Special',
            'description' => 'Delicious Indonesian salad with peanut sauce.',
        ]);
    }

    public function test_can_create_recipe_with_ingredients_and_directions()
    {
        $recipe = Recipe::factory()->create([
            'name' => 'Gado-Gado Special',
        ]);

        $recipe->ingredients()->createMany([
            ['name' => 'Kacang Panjang', 'quantity' => '50g'],
            ['name' => 'Tahu', 'quantity' => '100g'],
            ['name' => 'Saus Kacang', 'quantity' => '100ml'],
        ]);

        $recipe->directions()->createMany([
            ['step_number' => 1, 'instruction' => 'Kukus semua sayur.'],
            ['step_number' => 2, 'instruction' => 'Tuangkan saus kacang.'],
        ]);

        $this->assertEquals(3, $recipe->ingredients()->count());
        $this->assertEquals(2, $recipe->directions()->count());

        $this->assertDatabaseHas('ingredients', ['name' => 'Kacang Panjang']);
        $this->assertDatabaseHas('directions', ['instruction' => 'Kukus semua sayur.']);
    }
}
