<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\WebSearch;
use Stringable;

class WebSearchAgent implements Agent, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return implode(' ', [
            'You are a research assistant with access to the web.',
            'When a user asks about current events, the latest news,',
            'or any topic requiring up-to-date information,',
            'use a web search engine to find reliable and current data.',
            'Always cite your sources when presenting information',
            'found on the internet. Be concise and factual.',
        ]);
    }

    /**
     * Get the tools available to the agent.
     *
     * @return WebSearch[]
     */
    public function tools(): iterable
    {
        return [
            // -----------------------------------------------
            // max - The maximum number of results
            // allow - Whitelisted domains
            // -----------------------------------------------
            (new WebSearch)
                ->max(5)
                ->allow(
                    [
                        'laravel.com',
                        'laravel-news.com',
                        'github.com',
                    ]
                ),
        ];
    }
}
