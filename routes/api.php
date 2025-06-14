<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmergencyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Emergency routes
    Route::get('/emergencies', [EmergencyController::class, 'index']);
    Route::post('/emergencies', [EmergencyController::class, 'store']);
    Route::get('/emergencies/{emergency}', [EmergencyController::class, 'show']);
    Route::put('/emergencies/{emergency}', [EmergencyController::class, 'update']);
    Route::delete('/emergencies/{emergency}', [EmergencyController::class, 'destroy']);
});