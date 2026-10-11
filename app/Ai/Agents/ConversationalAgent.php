<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;
use Laravel\Ai\Concerns\RemembersConversations;

class ConversationalAgent implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are a helpful assistant for a Laravel AI SDK.',
            'You remember everything discussed in the current conversation.',
            'If the user mentions something previously discussed, recognise it and expand on it.',
            'Keep responses concise - 2 to 3 sentences maximum.',
        ]);
    }
}
