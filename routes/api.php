<?php

use App\Http\Controllers\Api\TravelOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'message' => 'API funcionando!!!'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/travel-orders', [TravelOrderController::class, 'index']);
    Route::get('/travel-orders/{id}', [TravelOrderController::class, 'show']);
    Route::post('/travel-orders', [TravelOrderController::class, 'store']);
    Route::patch('/travel-orders/{id}/status', [TravelOrderController::class, 'updateStatus']);
});

