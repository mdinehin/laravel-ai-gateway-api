<?php

namespace App\Http\Controllers\Api\Path2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Ai\Agents\BaseAgent;

class BaseAgentController extends Controller
{
    public function promptAgentWithInput(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $inputText = $request->input('text');

        // -----------------------------------------------
        // Set the specific AI provider and model at
        // runtime. Example:
        // provider: 'gemini'
        // model: 'gemini-2.5-flash-lite'
        // -----------------------------------------------
        $response = (new BaseAgent)
        ->prompt(
            $inputText,
            provider: 'openai'
        );

        return response()->json([
            'status' => 'success',
            'input_text' => $inputText,
            'response' => (string) $response,
        ]);
    }
}
