<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Direction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_recipe_with_ingredients_and_directions()
    {
        // Arrange: Siapkan data recipe
        $recipeData = [
            'image_url' => 'https://example.com/150',
            'name' => 'Gado-Gado Special',
            'description' => 'Delicious Indonesian salad with peanut sauce.',
            'servings' => 4,
            'duration' => 30,
            'rating' => 5,
            'created_by' => 1,
        ];

        // Buat recipe
        $recipe = Recipe::create($recipeData);

        // Tambahkan ingredients
        $recipe->ingredients()->createMany([
            ['description' => 'Kacang Panjang', 'quantity' => '50', 'unit' => 'g'],
            ['description' => 'Tahu', 'quantity' => '100', 'unit' => 'g'],
            ['description' => 'Saus Kacang', 'quantity' => '100', 'unit' => 'ml'],
        ]);

        // Tambahkan directions
        $recipe->directions()->createMany([
            ['image_url' => 'https://example.com/201', 'description' => 'Kukus semua sayur.'],
            ['image_url' => 'https://example.com/202', 'description' => 'Tuangkan saus kacang.'],
        ]);

        // Cek data recipe tersimpan di database
        $this->assertDatabaseHas('recipes', [
            'name' => 'Gado-Gado Special',
            'description' => 'Delicious Indonesian salad with peanut sauce.',
        ]);

        // Assert: Cek ingredients tersimpan
        $this->assertDatabaseHas('ingredients', ['description' => 'Kacang Panjang']);
        $this->assertDatabaseHas('ingredients', ['description' => 'Tahu']);

        // Assert: Cek directions tersimpan
        $this->assertDatabaseHas('directions', ['description' => 'Kukus semua sayur.']);
        $this->assertDatabaseHas('directions', ['description' => 'Tuangkan saus kacang.']);

        // Assert: Validasi jumlah ingredients dan directions
        $this->assertEquals(3, $recipe->ingredients()->count());
        $this->assertEquals(2, $recipe->directions()->count());
    }
}
