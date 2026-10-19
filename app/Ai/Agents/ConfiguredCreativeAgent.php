<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider('gemini')]
#[Model('gemini-2.5-flash-lite')]
#[Temperature(0.9)]
#[MaxTokens(2048)]
#[Timeout(120)]
class ConfiguredCreativeAgent implements Agent
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are a talented creative writer with a vivid imagination.',
            'Write engaging, original content filled with rich descriptions,',
            'compelling characters, and surprising plot twists.',
            'Adapt your style to the required genre or topic.',
        ]);
    }
}
