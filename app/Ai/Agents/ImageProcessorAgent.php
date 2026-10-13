<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

class ImageProcessorAgent implements Agent
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are an image analyst.',
            'When the user attaches an image, analse it thoroughly',
            'and answer the question asked in relation to it.',
            'Be specific and detailed in your descriptions.',
            'If there is text in the image, transcribe it accurately.',
            'If there are charts of data in the image,',
            'extract and present the information clearly.',
        ]);
    }
}
