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
            'message' => 'required|string|max:1000'
        ]);

        $message = $request->input('message');

        // -----------------------------------------------
        // Set the specific AI provider and model at
        // runtime. Example:
        // provider: "gemini"
        // model: "gemini-2.5-flash-lite"
        // -----------------------------------------------
        $response = (new BaseAgent)->prompt(
            $message,
            provider: "openai"
        );

        return response()->json([
            'status' => 'success',
            'user_message' => $message,
            'response' => (string) $response,
        ]);
    }
}
