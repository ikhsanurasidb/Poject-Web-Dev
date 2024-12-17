<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Direction;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;


class RecipeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $duration = $request->input('duration');

        // Build the query with optional search and duration filtering
        $query = Recipe::with(['ingredients', 'directions'])
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('duration', 'like', "%{$search}%"); // Adjust based on your search needs
            })
            ->when($duration, function ($query) use ($duration) {
                return $query->where('duration', $duration);
            })
            ->latest();

        // Paginate the results
        $recipes = $query->paginate(6);

        // Return recipes and pagination metadata
        return response()->json([
            'data' => $recipes->items(), // The actual recipes
            'meta' => [
                'current_page' => $recipes->currentPage(),
                'last_page' => $recipes->lastPage(),
                'total' => $recipes->total(),
                'per_page' => $recipes->perPage(),
            ],
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'name' => 'required|string',
            'description'=> 'required|string',
            'servings'=> 'required|integer',
            'duration' => 'required|integer',
            'rating' => 'nullable|integer',
            'created_by'=> 'required|string',
            'ingredients' => 'required|array',
            'directions' => 'required|array',
            'ingredients.*.quantity' => 'required|integer',
            'ingredients.*.description' => 'required|string',
            'directions.*.description' => 'required|string',
        ]);

        # Upload image to Cloudinary
        try {
            if ($request->hasFile('image')) {
                $uploadedImage = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'recipes',
                ]);

                // Get the uploaded image URL
                $imageUrl = $uploadedImage->getSecurePath();
            } else {
                return response()->json([
                    'message' => 'Image file is required.',
                ], 400);
            }
        } catch (Exception $e) {
            // Return a JSON response with a 500 status code
            return response()->json([
                'message' => 'Failed to upload image. Please try again later.',
            ], 500);
        }


        // Get the uploaded image URL
        $imageUrl = $uploadedImage->getSecurePath();

        $recipe = Recipe::create([
            'image_url' => $imageUrl,
            'name' => $request->name,
            'description'=> $request->description,
            'servings' => $request->servings,
            'duration' => $request->duration,
            'rating' => $request->rating,
            'created_by' => $request->created_by,
        ]);

        foreach ($request->ingredients as $ingredientData) {
            $ingredientData['recipe_id'] = $recipe->id;
            $ingredientData['unit'] = $ingredientData['unit'] ?? '';
            Ingredient::create($ingredientData);
        }

        foreach ($request->directions as $directionData) {
            $directionData['recipe_id'] = $recipe->id;
            $directionData['image_url'] = $directionData['image_url'] ?? 'https://example.com/default-direction.jpg';
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
