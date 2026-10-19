<?php

namespace App\Http\Controllers\Api\Path4;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Ai\Transcription;

class TranscriptionGeneratorController extends Controller
{
    public function transcribeAudio(Request $request): JsonResponse
    {
        $request->validate([
            'audio' => 'required|file|mimes:mp3,mp4,wav,m4a,webm|max:25600',
        ]);

        $audio = $request->file('audio');

        $transcript = Transcription::fromUpload($audio)
            ->generate();

        return response()->json([
            'status' => 'Generate text (transcription)',
            'filename' => $audio->getClientOriginalName(),
            'response' => (string) $transcript,
        ]);
    }
}
