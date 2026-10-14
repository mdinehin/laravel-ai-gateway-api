<?php

namespace App\Http\Controllers\Api\Path2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Ai\Agents\ConfiguredCreativeAgent;
use App\Ai\Agents\ConfiguredPreciseAgent;
use Laravel\Ai\Responses\StructuredAgentResponse;
use OpenApi\Attributes as OA;

class ConfiguredAgentController extends Controller
{
    #[OA\Post(
        path: "/path2/configured/creative",
        tags: ["Agents and Prompting"],
        security: [["bearerAuth" => []]],
        summary: "Creative Configured Agent",
        description: "Prompt a creative agent configured to have a high temperature.",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Prompt and genre used to get the repsonse.",
            content: new OA\JsonContent(
                required: [
                    "prompt",
                    "conversation_id",
                ],
                properties: [
                    new OA\Property(property: "prompt", type: "string", example: "Write the first paragraph of a crime novel."),
                    new OA\Property(property: "genre", type: "string", example: "mystery"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OK",
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: "response_object",
                            summary: "Response object #1",
                            value: [
                                "status" => "Creative writer (high temperature)",
                                "prompt" => "Genre: mystery. Task: Write the first paragraph of a crime novel.",
                                "response" => "The fog, thick as a shroud of forgotten secrets, ...",
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: "response_object",
                            summary: "Response object #1",
                            value: [
                                "message" => "Unauthenticated.",
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Unprocessable Content",
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: "response_object",
                            summary: "Response object #1",
                            value: [
                                "message" => "The genre field is required.",
                                "errors" => [
                                    "password" => [
                                        "The genre field is required.",
                                    ]
                                ],
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 500, description: "Internal Server Error"),
        ]
    )]
    public function writeCreatively(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
            'genre' => 'required|string|max:50',
        ]);

        $genre = $request->input('genre', 'general');
        $prompt = "Genre: {$genre}. Task: " . $request->input('prompt');

        /** @var StructuredAgentResponse $response */
        $response = (new ConfiguredCreativeAgent)
        ->prompt($prompt);

        return response()->json([
            'status' => 'Creative writer (high temperature)',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }

    #[OA\Post(
        path: "/path2/configured/precise",
        tags: ["Agents and Prompting"],
        security: [["bearerAuth" => []]],
        summary: "Precise Configured Agent",
        description: "Prompt a precise agent configured to have a low temperature.",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Prompt used to get the repsonse.",
            content: new OA\JsonContent(
                required: [
                    "prompt",
                ],
                properties: [
                    new OA\Property(
                        property: "prompt",
                        type: "string",
                        example: "Hi, my name is Sarah Chan, and I work at TechVault Inc. You can reach me at schan@techvault.io or call me at (415) 555-0192."
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OK",
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: "response_object",
                            summary: "Response object #1",
                            value: [
                                "status" => "Precise extractor (low temperature)",
                                "prompt" => "Hi, my name is Sarah Chan, and I work at TechVault Inc. You can reach me at schan@techvault.io or call me at (415) 555-0192",
                                "response" => [
                                    "name" => "Sarah Chan",
                                    "email" => "schan@techvault.io",
                                    "phone" => "(415) 555-0192",
                                    "company" => "TechVault Inc.",
                                ],
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: "response_object",
                            summary: "Response object #1",
                            value: [
                                "message" => "Unauthenticated.",
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Unprocessable Content",
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: "response_object",
                            summary: "Response object #1",
                            value: [
                                "message" => "The prompt field is required.",
                                "errors" => [
                                    "password" => [
                                        "The prompt field is required.",
                                    ]
                                ],
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 500, description: "Internal Server Error"),
        ]
    )]
    public function extractPrecisely(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        $prompt = $request->input('prompt');

        /** @var StructuredAgentResponse $response */
        $response = (new ConfiguredPreciseAgent)
        ->prompt('Extract the contact details from this text: ' . $prompt);

        return response()->json([
            'status' => 'Precise extractor (low temperature)',
            'prompt' => $prompt,
            'response' => [
                'name' => $response['name'],
                'email' => $response['email'],
                'phone' => $response['phone'],
                'company' => $response['company'],
            ],
        ]);
    }
}
