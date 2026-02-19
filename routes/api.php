<?php

use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::Post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'Can_create_user'])->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'can_order'])
    ->get('/order', function () {
        return response()->json(['message' => 'Order berhasil']);
    });

Route::middleware(['auth:sanctum', 'can_check'])
    ->get('/check', function () {
        return response()->json(['message' => 'Cek data OK']);
    });

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
});
