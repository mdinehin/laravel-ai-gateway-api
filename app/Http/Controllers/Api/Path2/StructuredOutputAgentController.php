<?php

namespace App\Http\Controllers\Api\Path2;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Ai\Agents\StructuredOutputAgent;
use Laravel\Ai\Responses\StructuredAgentResponse;
use function Laravel\Ai\{agent};

class StructuredOutputAgentController extends Controller
{
    public function analyseSentiment(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|max:2000',
        ]);

        $inputText = $request->input('text');

        /** @var StructuredAgentResponse $response */
        $response = (new StructuredOutputAgent)
        ->prompt($inputText);

        $jsonString = $response['text'];
        $structuredResponse = json_decode($jsonString, true);

        return response()->json([
            'status' => 'Sentiment Analysis (StructuredOutput)',
            'input_text' => $inputText,
            'response' => [
                'sentiment' => $response['sentiment'],
                'score' => $response['score'],
                'topics' => $response['topics'],
                'summary' => $response['summary'],
            ],
        ]);
    }

    public function simpleAnonymousAgent(): void
    {
        $response = agent(
            instructions: 'You are a helpful assistant who speaks like a pirate.'
        )->prompt('Tell me about Laravel.');

        echo $response;
    }

    public function structuredAnonymousAgent(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $inputText = $request->input('text');

        /** @var StructuredAgentResponse $response */
        $response = agent(
            instructions: implode(' ', [
                'You are a language identification and translation assistant.',
                'Identify the language of the source text and, if necessary, translate it into English.',
                'Estimate how many words are in the text.',
            ]),
            schema: fn (JsonSchema $schema) => [
                'detected_language' => $schema
                ->string()
                ->description('The detected language of the source text')
                ->required(),

                'is_english' => $schema
                ->boolean()
                ->description('Whether the text is already in English')
                ->required(),

                'english_translation' => $schema
                ->string()
                ->description('The English translation (same as source text if already in English)')
                ->required(),

                'source_word_count' => $schema
                ->integer()
                ->min(0)
                ->description('Approximate word count of the source text')
                ->required(),
            ]
        )->prompt($inputText);

        return response()->json([
            'status' => 'Anonymous agent with structured output',
            'input_text' => $inputText,
            'response' => [
                'detected_language' => $response['detected_language'],
                'is_english' => $response['is_english'],
                'english_translation' => $response['english_translation'],
                'source_word_count' => $response['source_word_count'],
            ],
        ]);
    }
}
