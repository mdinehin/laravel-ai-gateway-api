<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

class DocumentProcessorAgent implements Agent
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are a document analyst.',
            'When a user attaches a file and asks a question about it,',
            'carefully review the document and provide a comprehensive,',
            'precise answer based on its content.',
            'If summarising, include key points without omitting important details.',
            'If a user asks about a matter not covered by the document,',
            'they should be informed of this.',
        ]);
    }
}
