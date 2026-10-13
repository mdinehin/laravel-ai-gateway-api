<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Providers\Tools\WebFetch;

#[Provider('gemini')]
class WebFetchAgent implements Agent, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are a web page analyst.',
            'Upon receiving the URL, fetch the page content and present a clear analysis.',
            'You can summarise articles, extract key information, identify main topics,',
            'and answer questions about the content of any web page.',
            'Always provide the source URL when presenting your findings.',
        ]);
    }

    /**
     * Get the tools available to the agent.
     *
     * @return WebFetch[]
     */
    public function tools(): iterable
    {
        return [
            new WebFetch,
        ];
    }
}
