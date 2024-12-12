<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Direction;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Recipe::query();

        if ($request->has('duration')) {
            $query->where('duration', $request->duration);
        }

        $recipes = $query->with(['ingredients', 'directions'])->get();

        return response()->json($recipes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image_url' => 'required|string',
            'name' => 'required|string',
            'duration' => 'required|integer',
            'rating' => 'required|integer',
            'ingredients' => 'required|array',
            'directions' => 'required|array',
            'ingredients.*.quantity' => 'required|integer',
            'ingredients.*.description' => 'required|string',
            'directions.*.description' => 'required|string',
        ]);

        $recipe = Recipe::create($request->only(['image_url', 'name', 'duration', 'rating']));

        foreach ($request->ingredients as $ingredientData) {
            $ingredientData['recipe_id'] = $recipe->id;
            Ingredient::create($ingredientData);
        }

        foreach ($request->directions as $directionData) {
            $directionData['recipe_id'] = $recipe->id;
            $directionData['image_url'] = $directionData['image_url'] ?? 'https://example.com/default-direction.jpg'; // Default image URL
            Direction::create($directionData);
        }

        return response()->json($recipe->load(['ingredients', 'directions']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $recipe = Recipe::with(['ingredients', 'directions'])->findOrFail($id);
        return response()->json($recipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'image_url' => 'string',
            'name' => 'string',
            'duration' => 'integer',
            'rating' => 'integer',
        ]);

        $recipe = Recipe::findOrFail($id);
        $recipe->update($request->all());

        return response()->json($recipe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();

        return response()->json(null, 204);
    }
}
