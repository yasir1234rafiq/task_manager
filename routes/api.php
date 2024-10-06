<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskApiController;
use App\Http\Controllers\API\AuthController;

Route::post('/register', [AuthController::class, 'register']); // User Registration

Route::post('/login', [AuthController::class, 'login']);
// Protected Route

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/tasks', [TaskApiController::class, 'index']);
    Route::post('/tasks', [TaskApiController::class, 'store']);
    Route::get('/tasks/{task}', [TaskApiController::class, 'show']);
    Route::put('/tasks/{task}', [TaskApiController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskApiController::class, 'destroy']);
    Route::post('/tasks/{task}/feedback', [TaskApiController::class, 'submitFeedback']);
    Route::post('/logout', [AuthController::class, 'logout']);// New feedback route
});
//
//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
