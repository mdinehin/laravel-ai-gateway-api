<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\HasStructuredOutput;

class SentimentAnalyserAgent implements Agent
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are a sentiment analysis engine.',
            'Parse the given text and return structured data.',
            'The sentyment must be exactly one of: positive, negative, or neutral.',
            'The score must be between 1 (very negative) and 10 (very positive).',
            'Identify 1 to 5 of the most important topics covered in the text.',
            'Provide a concise, one-sentence summary of the overall sentiment.',
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'sentiment' => $schema
            ->string()
            ->enum([
                'positive',
                'negative',
                'neutral',
            ])
            ->description('The overall sentiment of the text')
            ->required(),

            'score' => $schema
            ->integer()
            ->min(1)
            ->max(10)
            ->description('Sentiment score from 1 (very negative) to 10 (very positive)')
            ->required(),

            'topics' => $schema
            ->array()
            ->items($schema->string())
            ->description('Key topics covered in the text')
            ->required(),

            'summary' => $schema
            ->string()
            ->description('A concise, one-sentence summary of the overall sentiment')
            ->required(),
        ];
    }
}
