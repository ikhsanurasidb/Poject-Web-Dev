<?php

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('recipes', RecipeController::class)->middleware('auth:sanctum');

Route::post('/register', [RegisterController::class,'register']);
Route::post('/login', [RegisterController::class,'login']);
Route::post('/logout', [RegisterController::class,'logout'])->middleware
('auth:sanctum');


