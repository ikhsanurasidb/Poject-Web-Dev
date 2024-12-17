<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/{any}', function () {
//     return view('welcome');
// })->where('any', '.*');

Route::get('/build/{path}', function ($path) {
    return response()->file(public_path("build/$path"));
});

Route::get('/{pathMatch}', function () {
    return view('welcome');
})->where('pathMatch', '.*');

Route::post('/test-cloudinary-upload', function (Request $request) {
    $request->validate([
        'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate the uploaded file
    ]);

    try {
        // Upload image to Cloudinary
        $uploadedImage = Cloudinary::upload($request->file('image')->getRealPath(), [
            'folder' => 'test-folder', // Optional: Specify a folder
        ]);

        // Get the uploaded image URL
        $imageUrl = $uploadedImage->getSecurePath();

        return response()->json([
            'message' => 'Upload successful!',
            'image_url' => $imageUrl,
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Upload failed.',
            'error' => $e->getMessage(),
        ], 500);
    }
});
