<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/equipment', [EquipmentController::class, 'index']);

Route::get('/equipment/{id}', [EquipmentController::class, 'show']);

Route::get('/equipment/{id}/Review', [EquipmentController::class, 'showReview']);

Route::get('/equipment/{id}/Price', [EquipmentController::class, 'showLocationPrice']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

Route::put('/users/{id}', [UserController::class, 'update']);

Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);