<?php

namespace App\Http\Controllers\Api\Path2;

use App\Ai\Agents\ConversationalAgent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ConversationalAgentController extends Controller
{
    #[OA\Post(
        path: '/path2/conversations/start',
        tags: ['Agents and Prompting'],
        security: [['bearerAuth' => []]],
        summary: 'Conversational Agent (Start)',
        description: 'Start conversation with an AI agent.',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Prompt used to get the repsonse.',
            content: new OA\JsonContent(
                required: [
                    'prompt',
                ],
                properties: [
                    new OA\Property(
                        property: 'prompt',
                        type: 'string',
                        example: 'Hi, my name is Michal, I am a web developer, and I would like to learn.'
                    ),
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
                                'status' => 'Conversation started',
                                'conversation_id' => '019f8500-ef1b-7350-9629-60dcb4ff20a0',
                                'prompt' => 'Hi, my name is Michal, I am a web developer, and I would like to learn.',
                                'response' => 'Hi Michal - nice to meet you.',
                                'hint' => 'Save the conversation_id',
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
    public function startConversation(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        $prompt = $request->input('prompt');

        $response = (new ConversationalAgent)
            ->forUser($user)
            ->prompt($prompt);

        return response()->json([
            'status' => 'Conversation started',
            'conversation_id' => $response->conversationId,
            'prompt' => $prompt,
            'response' => (string) $response,
            'hint' => 'Save the conversation_id',
        ]);
    }

    #[OA\Post(
        path: '/path2/conversations/continue',
        tags: ['Agents and Prompting'],
        security: [['bearerAuth' => []]],
        summary: 'Conversational Agent (Continue)',
        description: 'Continue conversation with the AI agent.',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Prompt and conversation_id used to get the repsonse.',
            content: new OA\JsonContent(
                required: [
                    'prompt',
                    'conversation_id',
                ],
                properties: [
                    new OA\Property(property: 'prompt', type: 'string', example: 'What is my name?'),
                    new OA\Property(property: 'conversation_id', type: 'string', example: '019f8500-ef1b-7350-9629-60dcb4ff20a0'),
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
                                'status' => 'Conversation continues',
                                'conversation_id' => '019f8500-ef1b-7350-9629-60dcb4ff20a0',
                                'prompt' => 'What is my name?',
                                'response' => 'Your name is Michal.',
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
                                'message' => 'The conversation id field is required.',
                                'errors' => [
                                    'password' => [
                                        'The conversation id field is required.',
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
    public function continueConversation(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|string',
            'prompt' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        $prompt = $request->input('prompt');
        $conversationId = $request->input('conversation_id');

        $response = (new ConversationalAgent)
            ->continue($conversationId, as: $user)
            ->prompt($prompt);

        return response()->json([
            'status' => 'Conversation continues',
            'conversation_id' => $response->conversationId,
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }
}
