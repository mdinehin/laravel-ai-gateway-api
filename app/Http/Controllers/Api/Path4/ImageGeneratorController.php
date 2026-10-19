<?php

namespace App\Http\Controllers\Api\Path4;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Ai\Image;

class ImageGeneratorController extends Controller
{
    public function generateImage(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $prompt = $request->input('prompt');

        // -----------------------------------------------
        // Providers: openai, gemini, xai
        // -----------------------------------------------
        $image = Image::of($prompt)
            ->landscape()
            ->quality('low')
            ->generate(provider: 'openai');

        $path = $image->store('', 'public');

        // -----------------------------------------------
        // Create storage simbolic link (simlink) between
        // root\public\storage and root\storage\app\public
        // to access the generated image through a URL.
        // Command: php artisan storage:link
        // -----------------------------------------------
        return response()->json([
            'status' => 'Generate image to local storage',
            'prompt' => $prompt,
            'path' => $path,
            'url' => asset('storage/'.$path),
        ]);
    }
}
