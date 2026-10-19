<?php

namespace App\Http\Controllers\Api\Path2;

use App\Ai\Agents\StructuredOutputAgent;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Ai\Responses\StructuredAgentResponse;
use OpenApi\Attributes as OA;

use function Laravel\Ai\agent;

class StructuredOutputAgentController extends Controller
{
    #[OA\Post(
        path: '/path2/analysis/sentiment',
        tags: ['Agents and Prompting'],
        security: [['bearerAuth' => []]],
        summary: 'Analyse User Sentiment',
        description: 'Prompt an AI agent to analyse user sentiment.',
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
                        example: 'I absolutely love this phone! The camera is superb, and the battery lasts all day. The only downside is the price.'
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
                                'status' => 'Sentiment Analysis (StructuredOutput)',
                                'prompt' => 'I absolutely love this phone! The camera is superb, and the battery lasts all day. The only downside is the price.',
                                'response' => [
                                    'sentiment' => 'positive',
                                    'score' => 9,
                                    'topics' => [
                                        'phone',
                                        'camera quality',
                                        'battery life',
                                        'price',
                                    ],
                                    'summary' => "The sentiment is strongly positive, praising the phone's excellent camera and long battery life while noting the high price as a minor drawback.",
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
    public function analyseSentiment(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        $prompt = $request->input('prompt');

        /** @var StructuredAgentResponse $response */
        $response = (new StructuredOutputAgent)
            ->prompt($prompt);

        return response()->json([
            'status' => 'Agent with structured output',
            'prompt' => $prompt,
            'response' => [
                'sentiment' => $response['sentiment'],
                'score' => $response['score'],
                'topics' => $response['topics'],
                'summary' => $response['summary'],
            ],
        ]);
    }

    #[OA\Get(
        path: '/path2/anonymous/simple',
        tags: ['Agents and Prompting'],
        security: [['bearerAuth' => []]],
        summary: 'Simple Anonymous Agent',
        description: 'Prompt a simple, anonymous agent who speaks like a pirate.',
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
                                'status' => 'Simple anonymous agent',
                                'response' => 'Ahoy! Laravel be a popular PHP web application framework.',
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
    public function simpleAnonymousAgent(): void
    {
        $response = agent(
            instructions: 'You are a helpful assistant who speaks like a pirate.'
        )->prompt('Tell me about Laravel.');

        echo $response;
    }

    #[OA\Post(
        path: '/path2/anonymous/structured',
        tags: ['Agents and Prompting'],
        security: [['bearerAuth' => []]],
        summary: 'Structured Anonymous Agent',
        description: 'Prompt a structured, anonymous agent equipped with language translation capabilities.',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Prompt used to get the repsonse.',
            content: new OA\JsonContent(
                required: [
                    'prompt',
                ],
                properties: [
                    new OA\Property(property: 'prompt', type: 'string', example: 'Gracias por la respuesta.'),
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
                                'status' => 'Structured anonymous agent',
                                'input_text' => 'Gracias por la respuesta.',
                                'response' => [
                                    'detected_language' => 'Spanish',
                                    'is_english' => false,
                                    'english_translation' => 'Thank you for the response.',
                                    'source_word_count' => 4,
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
    public function structuredAnonymousAgent(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        $prompt = $request->input('prompt');

        /** @var StructuredAgentResponse $response */
        $response = agent(
            instructions: implode(' ', [
                'You are a language identification and translation assistant.',
                'Identify the language of the source text and, if necessary, translate it into English.',
                'Estimate how many words are in the text.',
            ]),
            schema: fn (JsonSchema $schema) => [
                'detected_language' => $schema
                    ->string()
                    ->description('The detected language of the source text')
                    ->required(),

                'is_english' => $schema
                    ->boolean()
                    ->description('Whether the text is already in English')
                    ->required(),

                'english_translation' => $schema
                    ->string()
                    ->description('The English translation (same as source text if already in English)')
                    ->required(),

                'source_word_count' => $schema
                    ->integer()
                    ->min(0)
                    ->description('Approximate word count of the source text')
                    ->required(),
            ]
        )->prompt($prompt);

        return response()->json([
            'status' => 'Structured anonymous agent',
            'input_text' => $prompt,
            'response' => [
                'detected_language' => $response['detected_language'],
                'is_english' => $response['is_english'],
                'english_translation' => $response['english_translation'],
                'source_word_count' => $response['source_word_count'],
            ],
        ]);
    }
}
