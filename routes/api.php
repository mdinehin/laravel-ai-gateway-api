<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Path1\SetupController;
use App\Http\Controllers\Api\Path2\BaseAgentController;
use App\Http\Controllers\Api\Path2\ConversationalAgentController;
use App\Http\Controllers\Api\Path2\StructuredOutputAgentController;
use App\Http\Controllers\Api\Path2\ConfiguredAgentController;
use App\Http\Controllers\Api\Path3\HasToolsController;
use App\Http\Controllers\Api\Path3\WebSearchController;

/**
 * Path 1 group
 */

Route::prefix('path1')->group(function () {
    Route::get('/health-check',
    [SetupController::class, 'healthCheck']);

    Route::get('/verify-ai-sdk',
    [SetupController::class, 'verifyAiSdk']);

    Route::post('/generate-token',
    [SetupController::class, 'generateToken']);
});

Route::prefix('path1')->middleware('auth:sanctum')->group(function () {
    Route::get('/me',
    [SetupController::class, 'me']);
});

/**
 * Path 2 group
 */

Route::prefix('path2')->middleware('auth:sanctum')->group(function () {
    Route::post('/base-agent',
    [BaseAgentController::class, 'promptAgentWithInput']);

    Route::post('/conversations/start',
    [ConversationalAgentController::class, 'startConversation']);

    Route::post('/conversations/continue',
    [ConversationalAgentController::class, 'continueConversation']);

    Route::post('/analysis/sentiment',
    [StructuredOutputAgentController::class, 'analyseSentiment']);

    Route::get('/anonymous/simple',
    [StructuredOutputAgentController::class, 'simpleAnonymousAgent']);

    Route::post('/anonymous/structured',
    [StructuredOutputAgentController::class, 'structuredAnonymousAgent']);

    Route::post('/configured/creative',
    [ConfiguredAgentController::class, 'writeCreatively']);

    Route::post('/configured/precise',
    [ConfiguredAgentController::class, 'extractPrecisely']);
});

/**
 * Path 3 group
 */

Route::prefix('path3')->group(function () {
    Route::post('/tools/time-assistant',
    [HasToolsController::class, 'getRequestedTime']);

    Route::post('/web/search',
    [WebSearchController::class, 'searchWeb']);
});
