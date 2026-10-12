<?php

namespace App\Http\Controllers\Api\Path2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Ai\Agents\ConfiguredCreativeAgent;
use App\Ai\Agents\ConfiguredPreciseAgent;
use Laravel\Ai\Responses\StructuredAgentResponse;

class ConfiguredAgentController extends Controller
{
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
            'status' => 'Creative Writer (High Temperature)',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }

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
            'status' => 'Precise Extractor (Low Temperature)',
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
