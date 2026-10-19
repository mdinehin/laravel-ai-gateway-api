<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CurrentTimeLookupTool;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Stringable;

class HasToolsAgent implements Agent, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are a helpful assistant with access to real-time information.',
            'When a user asks for the current time, date, or day of the week in any timezone,',
            'use the CurrentTimeLookupTool class to obtain accurate data.',
            'Present the information in a clear and accessible way.',
        ]);
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new CurrentTimeLookupTool,
        ];
    }
}
