<?php

namespace App\Http\Controllers\Api\Path4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Ai\Files;
use Laravel\Ai\Image;

class ImageGeneratorController extends Controller
{
   public function generateImage(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $prompt = $request->input('prompt');

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
            'status' => 'Image Generator Controller',
            'prompt' => $prompt,
            'path' => $path,
            'url' => asset('storage/' . $path),
        ]);
    }
}
