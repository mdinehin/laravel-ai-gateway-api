<?php

namespace App\Http\Controllers\Api\Path3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use function Laravel\Ai\{agent};
use Laravel\Ai\Responses\StructuredAgentResponse;
use App\Ai\Agents\HasToolsAgent;

class HasToolsController extends Controller
{
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
            'status' => 'HasTools Controller',
            'prompt' => $prompt,
            'without_tools' => (string) $withoutTools,
            'response' => (string) $response,
        ]);
    }
}
