<?php

namespace App\Http\Controllers\Api\Path2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Ai\Agents\BaseAgent;

class AgentPromptingController extends Controller
{
    public function promptAgent(): JsonResponse
    {
        $agent = BaseAgent::make();

        $response = $agent->prompt('What are you and what can you help mme with?');

        return response()->json([
            'status' => 'success',
            'response' => (string) $response,
        ]);
    }
}
