<?php

namespace App\Http\Controllers\Api\Path4;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Ai\Audio;

class AudioGeneratorController extends Controller
{
    public function generateAudio(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $prompt = $request->input('prompt');

        // -----------------------------------------------
        // Instructions don't seem to work at the moment.
        // ->instructions('Speak like a pirate.')
        // Providers: openai, elevenlabs
        // -----------------------------------------------
        $audio = Audio::of($prompt)
            ->male()
            ->generate(provider: 'openai');

        $path = $audio->store('audio', 'public');

        // -----------------------------------------------
        // Create storage simbolic link (simlink) between
        // root\public\storage and root\storage\app\public
        // to access the generated image through a URL.
        // Command: php artisan storage:link
        // -----------------------------------------------
        return response()->json([
            'status' => 'Generate audio file to local storage',
            'prompt' => $prompt,
            'path' => $path,
            'url' => asset('storage/'.$path),
        ]);
    }
}
