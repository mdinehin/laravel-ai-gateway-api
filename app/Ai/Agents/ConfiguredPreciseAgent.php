<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Timeout;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\HasStructuredOutput;

#[Provider('openai')]
#[Model('gpt-4o-mini')]
#[Temperature(0.0)]
#[MaxTokens(512)]
#[Timeout(120)]
class ConfiguredPreciseAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are a precise data extraction assistant.',
            'Extract exactly the information requested.',
            'Present the facts and be concise.',
            'Do not add opinions or embellishments.',
            'If data was not provided in the input,',
            'null or empty values should be used.',
        ]);
    }

    /**
     * @return mixed[]
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema
            ->string()
            ->description('Full name of the person.')
            ->required(),

            'email' => $schema
            ->string()
            ->description('E-mail address, if found; otherwise, empty.')
            ->required(),

            'phone' => $schema
            ->string()
            ->description('Phone number, if found; otherwise, empty.')
            ->required(),

            'company' => $schema
            ->string()
            ->description('Name of the company or organization, if provided.')
            ->required(),
        ];
    }
}
