<?php

namespace App\Http\Controllers\Api\Path3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Ai\Responses\StructuredAgentResponse;
use App\Ai\Agents\WebSearchAgent;
use App\Ai\Agents\WebFetchAgent;

class WebSearchController extends Controller
{
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
            'status' => 'Web Search Controller',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }

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
            'status' => 'Web Fetch Controller',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }
}
