<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceProviderController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReviewController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/client', [AuthController::class, 'registerClient']);


    Route::apiResource('service-providers', ServiceProviderController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('bookings', BookingController::class);
    Route::apiResource('reviews', ReviewController::class);

    // Additional routes
    Route::get('/service-providers/category/{category}', [ServiceProviderController::class, 'byCategory']);
    Route::post('/reviews/voice', [ReviewController::class, 'storeVoiceNote']);


Route::get('/test', function () {
    return response()->json([
        'message' => 'Hello, World!'
    ]);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/provider/profile', function (Request $request) {
        return response()->json($request->user());
    });
    Route::post('/logout', [AuthController::class, 'logout']);
     Route::put('/provider/profile', [ServiceProviderController::class, 'updateProfile']);
});
