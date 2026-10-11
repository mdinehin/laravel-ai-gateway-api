<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Chapter1\SetupController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('chapter1')->group(function () {
    Route::get('/health-check', [SetupController::class, 'healthCheck']);
    Route::get('/verify-ai-sdk', [SetupController::class, 'verifyAiSdk']);
    Route::post('/generate-token', [SetupController::class, 'generateToken']);
});

Route::prefix('chapter1')->middleware('auth:sanctum')->group(function () {
    Route::get('/me', [SetupController::class, 'me']);
});
