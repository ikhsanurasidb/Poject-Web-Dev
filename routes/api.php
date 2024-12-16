<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('recipes', RecipeController::class);
    Route::post('/logout', [RegisterController::class, 'logout']);
});

Route::get('/test-connection', function () {
    try {
        \DB::connection()->getPdo();
        return response()->json(['message' => 'Database connection is successful'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Could not connect to the database. Please check your configuration.'], 500);
    }
});