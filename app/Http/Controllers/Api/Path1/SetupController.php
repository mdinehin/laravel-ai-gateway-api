<?php

namespace App\Http\Controllers\Api\Path1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use OpenApi\Attributes as OA;

class SetupController extends Controller
{
    /**
     * Health Check: Verify the API is running.
     *
     * This is a simple public endpoint to confirm the Laravel
     * application is up and responding to API requests.
     */
    #[OA\Get(
        path: '/path1/health-check',
        tags: ['System Configuration Check'],
        summary: 'Health Check',
        description: 'Check if the Laravel API is running.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'response_object',
                            summary: 'Response object #1',
                            value: [
                                'status' => 'ok',
                                'message' => 'Laravel API is running.',
                                'laravel_version' => '13.18.1',
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Internal Server Error'),
        ]
    )]
    public function healthCheck(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Laravel API is running.',
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
     */
    #[OA\Get(
        path: '/path1/verify-ai-sdk',
        tags: ['System Configuration Check'],
        summary: 'Verify AI Software Development Kit (SDK)',
        description: 'Check if the Laravel AI Software Development Kit (SDK) is running.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'response_object',
                            summary: 'Response object #1',
                            value: [
                                'status' => 'ready',
                                'checks' => [
                                    'ai_config_loaded' => true,
                                    'conversations_table_exists' => true,
                                    'messages_table_exists' => true,
                                    'configured_providers' => [
                                        'openai',
                                        'gemini',
                                    ],
                                ],
                                'message' => 'AI SDK is fully set up and ready to go.',
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Internal Server Error'),
        ]
    )]
    public function verifyAiSdk(): JsonResponse
    {
        // -----------------------------------------------
        // Check 1: Is the AI config file loaded?
        // After publishing, config/ai.php should exist
        // and be accessible via config('ai').
        // -----------------------------------------------
        $aiConfigLoaded = ! is_null(config('ai'));

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
            'openai' => ! empty(config('ai.providers.openai.key')),
            'anthropic' => ! empty(config('ai.providers.anthropic.key')),
            'gemini' => ! empty(config('ai.providers.gemini.key')),
            'cohere' => ! empty(config('ai.providers.cohere.key')),
            'mistral' => ! empty(config('ai.providers.mistral.key')),
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
                ? 'AI SDK is fully set up and ready to go.'
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
     */
    #[OA\Post(
        path: '/path1/generate-token',
        tags: ['System Configuration Check'],
        summary: 'Generate Bearer Token',
        description: 'Generates a bearer token used for authenticated access to secured endpoints.',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'User email and password used for authorization.',
            content: new OA\JsonContent(
                required: [
                    'email',
                    'password',
                ],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'demouser@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'response_object',
                            summary: 'Response object #1',
                            value: [
                                'status' => 'success',
                                'message' => 'Token generated successfully. Use this as your Bearer token for all future requests.',
                                'token' => '[TOKEN_STRING]',
                                'user' => [
                                    'id' => 1,
                                    'name' => 'AI SDK Demo User',
                                    'email' => 'demouser@example.com',
                                ],
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'response_object',
                            summary: 'Response object #1',
                            value: [
                                'status' => 'error',
                                'message' => 'Invalid credentials.',
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Unprocessable Content',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'response_object',
                            summary: 'Response object #1',
                            value: [
                                'message' => 'The password field is required.',
                                'errors' => [
                                    'password' => [
                                        'The password field is required.',
                                    ],
                                ],
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Internal Server Error'),
        ]
    )]
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
     */
    #[OA\Get(
        path: '/path1/me',
        tags: ['System Configuration Check'],
        security: [['bearerAuth' => []]],
        summary: 'Verify Bearer Token',
        description: 'Check if the bearer token returns the correct user.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'response_object',
                            summary: 'Response object #1',
                            value: [
                                'status' => 'success',
                                'user' => [
                                    'id' => 1,
                                    'name' => 'AI SDK Demo User',
                                    'email' => 'demouser@example.com',
                                ],
                                'message' => 'You are authenticated and ready to go.',
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'response_object',
                            summary: 'Response object #1',
                            value: [
                                'message' => 'Unauthenticated.',
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Internal Server Error'),
        ]
    )]
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'message' => 'You are authenticated and ready to go.',
        ]);
    }
}
