<?php

namespace App\Http\Controllers\Api\Path3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use function Laravel\Ai\{agent};
use Laravel\Ai\Responses\StructuredAgentResponse;
use App\Ai\Agents\HasToolsAgent;
use OpenApi\Attributes as OA;

class HasToolsController extends Controller
{
    #[OA\Post(
        path: "/path3/tools/time-assistant",
        tags: ["Tools and Web Search"],
        security: [["bearerAuth" => []]],
        summary: "Tool-assisted Agent",
        description: "Prompt a tool-assisted agent with access to real-time information.",
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
                        example: "What is the local time in London?"
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
                                "status" => "Tool-assisted agent",
                                "prompt" => "What is the local time in London?",
                                "response" => "The local time in London is 5:00 PM on Thursday, Jul 30, 2026.",
                                "without_tools" => "The local time in London is 4:49 PM (16:49) on Tuesday, September 30, 2025.",
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
    public function getRequestedTime(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $prompt = $request->input('prompt');

        $withoutTools = agent(
            instructions: implode(' ', [
            'You are a helpful assistant with access to real-time information.',
            'When a user asks for the current time, date, or day of the week in any timezone,',
            'use the CurrentTimeLookupTool class to obtain accurate data.',
            'Present the information in a clear and accessible way.',
        ]))->prompt($prompt);

        /** @var StructuredAgentResponse $response */
        $response = (new HasToolsAgent)
        ->prompt($prompt);

        return response()->json([
            'status' => 'Tool-assisted agent',
            'prompt' => $prompt,
            'response' => (string) $response,
            'without_tools' => (string) $withoutTools,
        ]);
    }
}
