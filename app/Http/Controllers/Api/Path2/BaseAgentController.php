<?php

namespace App\Http\Controllers\Api\Path2;

use App\Ai\Agents\BaseAgent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BaseAgentController extends Controller
{
    #[OA\Post(
        path: '/path2/base-agent',
        tags: ['Agents and Prompting'],
        security: [['bearerAuth' => []]],
        summary: 'Base Agent',
        description: 'A base AI agent that contains a simple instruction.',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Prompt used to get the repsonse.',
            content: new OA\JsonContent(
                required: [
                    'prompt',
                ],
                properties: [
                    new OA\Property(property: 'prompt', type: 'string', example: 'What is the capital of Australia?'),
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
                                'status' => 'Base agent',
                                'prompt' => 'What is the capital of Australia?',
                                'response' => 'Canberra.',
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
            new OA\Response(
                response: 422,
                description: 'Unprocessable Content',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'response_object',
                            summary: 'Response object #1',
                            value: [
                                'message' => 'The prompt field is required.',
                                'errors' => [
                                    'password' => [
                                        'The prompt field is required.',
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
    public function promptAgentWithInput(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        $prompt = $request->input('prompt');

        // -----------------------------------------------
        // Set the specific AI provider and model at
        // runtime. Example:
        // provider: 'gemini'
        // model: 'gemini-2.5-flash-lite'
        // -----------------------------------------------
        $response = (new BaseAgent)
            ->prompt(
                $prompt,
                provider: 'openai'
            );

        return response()->json([
            'status' => 'Base agent',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }
}
