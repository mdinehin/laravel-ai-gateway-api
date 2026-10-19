<?php

namespace App\Ai\Tools;

use Carbon\Carbon;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CurrentTimeLookupTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return implode(' ', [
            'This tool gets the current date and time for a specific timezone.',
            'This tool is used when the user asks for the time or the current date, or needs time-related information.',
            'This tool returns the time in a human-readable format.',
        ]);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'timezone' => $schema
                ->string()
                ->description(implode(' ', [
                    'The timezone to get the current time for, in standard timezone format.',
                    "E.g., 'America/New_york', 'Europe/London', 'Asia/Tokyo', 'UTC'",
                ]))
                ->required(),
        ];
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $timezone = $request['timezone'];

        try {
            $now = Carbon::now($timezone);

            return (string) json_encode([
                'timezone' => $timezone,
                'datetime' => $now->toDateTimeString(),
                'date' => $now->toFormattedDateString(),
                'time' => $now->format('g:i A'),
                'day_of_week' => $now->format('l'),
                'ufc_offset' => $now->format('P'),
            ]);
        } catch (\Exception $e) {
            return "Error: Invalid timezone '{$timezone}'. Please use a valid timezone like 'Asia/Tokyo' or 'UTC'.";
        }
    }
}
