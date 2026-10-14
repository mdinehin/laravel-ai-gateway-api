<?php

namespace App\Http\Controllers\Api\Path3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Ai\Responses\StructuredAgentResponse;
use App\Ai\Agents\WebSearchAgent;
use App\Ai\Agents\WebFetchAgent;
use OpenApi\Attributes as OA;

class WebSearchController extends Controller
{
    #[OA\Post(
        path: "/path3/web/search",
        tags: ["Tools and Web Search"],
        security: [["bearerAuth" => []]],
        summary: "Web Search Agent",
        description: "Prompt an AI agent to search the web.",
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
                        example: "What is the Laravel AI SDK?"
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
                                "status" => "Web search agent",
                                "prompt" => "What is the Laravel AI SDK?",
                                "response" => "The **Laravel AI SDK** is a ...",
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
    public function searchWeb(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $prompt = $request->input('prompt');

        /** @var StructuredAgentResponse $response */
        $response = (new WebSearchAgent)
        ->prompt($prompt);

        return response()->json([
            'status' => 'Web search agent',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }

    #[OA\Post(
        path: "/path3/web/fetch",
        tags: ["Tools and Web Search"],
        security: [["bearerAuth" => []]],
        summary: "Web Fetch Agent",
        description: "Prompt an AI agent fetch web content.",
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
                        example: "What are the key differences between these two authentication packages? https://laravel.com/docs/13.x/sanctum and https://laravel.com/docs/13.x/passport"
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
                                "status" => "Web fetch agent",
                                "prompt" => "What are the key differences between these two authentication packages? https://laravel.com/docs/13.x/sanctum and https://laravel.com/docs/13.x/passport",
                                "response" => "The differences between ...",
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
    public function fetchWeb(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $prompt = $request->input('prompt');

        /** @var StructuredAgentResponse $response */
        $response = (new WebFetchAgent)
        ->prompt($prompt);

        return response()->json([
            'status' => 'Web fetch agent',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }
}
