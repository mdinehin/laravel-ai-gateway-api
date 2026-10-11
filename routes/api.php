<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Path1\SetupController;
use App\Http\Controllers\Api\Path2\AgentPromptingController;

/**
 * Path 1 group
 */

Route::prefix('path1')->group(function () {
    Route::get('/health-check', [SetupController::class, 'healthCheck']);
    Route::get('/verify-ai-sdk', [SetupController::class, 'verifyAiSdk']);
    Route::post('/generate-token', [SetupController::class, 'generateToken']);
});

Route::prefix('path1')->middleware('auth:sanctum')->group(function () {
    Route::get('/me', [SetupController::class, 'me']);
});

/**
 * Path 2 group
 */

Route::prefix('path2')->group(function () {
    Route::post('/prompt', [AgentPromptingController::class, 'promptAgentWithInput']);
});
