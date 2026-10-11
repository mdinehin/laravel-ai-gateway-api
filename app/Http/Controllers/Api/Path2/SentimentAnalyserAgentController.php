<?php

namespace App\Http\Controllers\Api\Path2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Ai\Agents\SentimentAnalyserAgent;

class SentimentAnalyserAgentController extends Controller
{
    public function analyseSentiment(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|max:2000',
        ]);

        $inputText = $request->input('text');
        $response = (array) (new SentimentAnalyserAgent)
        ->prompt($inputText);

        $jsonString = $response['text'];
        $analysis = json_decode($jsonString, true);

        return response()->json([
            'status' => 'Sentiment Analysis (StructuredOutput)',
            'input_text' => $inputText,
            'analysis' => $analysis,
        ]);
    }
}
