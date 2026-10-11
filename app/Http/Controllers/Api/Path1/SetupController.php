<?php

namespace App\Http\Controllers\Api\Path1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class SetupController extends Controller
{
    /**
     * Health Check: Verify the API is running.
     *
     * This is a simple public endpoint to confirm the Laravel
     * application is up and responding to API requests.
     *
     * @return JsonResponse
     */
    public function healthCheck(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Laravel AI API is running!',
            'laravel_version' => app()->version(),
        ]);
    }

    /**
     * Verify AI SDK Installation: Check that the AI SDK is
     * properly installed and configured.
     *
     * This checks:
     * 1. The AI config file exists and is loaded
     * 2. The required database tables are present
     * 3. At least one AI provider API key is configured
     *
     * @return JsonResponse
     */
    public function verifyAiSdk(): JsonResponse
    {
        // -----------------------------------------------
        // Check 1: Is the AI config file loaded?
        // After publishing, config/ai.php should exist
        // and be accessible via config('ai').
        // -----------------------------------------------
        $aiConfigLoaded = !is_null(config('ai'));

        // -----------------------------------------------
        // Check 2: Do the AI SDK database tables exist?
        // The AI SDK creates two tables for conversation
        // storage when you run migrations.
        // -----------------------------------------------
        $conversationsTableExists = Schema::hasTable('agent_conversations');
        $messagesTableExists = Schema::hasTable('agent_conversation_messages');

        // -----------------------------------------------
        // Check 3: Are any provider API keys configured?
        // We check for the most common providers.
        // -----------------------------------------------
        $configuredProviders = collect([
            'openai' => !empty(config('ai.providers.openai.key')),
            'anthropic' => !empty(config('ai.providers.anthropic.key')),
            'gemini' => !empty(config('ai.providers.gemini.key')),
            'cohere' => !empty(config('ai.providers.cohere.key')),
            'mistral' => !empty(config('ai.providers.mistral.key')),
        ])->filter()->keys()->all();

        // -----------------------------------------------
        // Compile the verification report.
        // -----------------------------------------------
        $allChecksPass = $aiConfigLoaded
            && $conversationsTableExists
            && $messagesTableExists
            && count($configuredProviders) > 0;

        return response()->json([
            'status' => $allChecksPass ? 'ready' : 'incomplete',
            'checks' => [
                'ai_config_loaded' => $aiConfigLoaded,
                'conversations_table_exists' => $conversationsTableExists,
                'messages_table_exists' => $messagesTableExists,
                'configured_providers' => $configuredProviders,
            ],
            'message' => $allChecksPass
                ? 'AI SDK is fully set up and ready to go!'
                : 'Some checks failed. Review the details above.',
        ]);
    }

    /**
     * Generate API Token: Create a Sanctum token for the
     * demo user so we can authenticate all future API requests.
     *
     * In a real app, you'd have a proper login flow.
     * For this, we use a simplified endpoint that
     * accepts email/password and returns a Bearer token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateToken(Request $request): JsonResponse
    {
        // -----------------------------------------------
        // Validate the incoming credentials.
        // -----------------------------------------------
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // -----------------------------------------------
        // Attempt to find the user by email.
        // -----------------------------------------------
        $user = User::where('email', $request->email)->first();

        // -----------------------------------------------
        // Verify the password matches.
        // -----------------------------------------------
        if (! $user || ! \Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // -----------------------------------------------
        // Create a new Sanctum API token.
        // The token name helps identify what it's for
        // when viewing active tokens later.
        // -----------------------------------------------
        $token = $user->createToken('ai-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Token generated successfully. Use this as your Bearer token for all future requests.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Get Authenticated User: Verify that the Bearer token
     * works by returning the current authenticated user.
     *
     * This endpoint requires authentication.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'message' => 'You are authenticated and ready to go!',
        ]);
    }
}
