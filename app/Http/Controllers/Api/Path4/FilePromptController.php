<?php

namespace App\Http\Controllers\Api\Path4;

use App\Ai\Agents\DocumentProcessorAgent;
use App\Ai\Agents\ImageProcessorAgent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Ai\Files\Document;
use Laravel\Ai\Files\Image;
use Laravel\Ai\Responses\StructuredAgentResponse;

class FilePromptController extends Controller
{
    public function processDocumentFromRequest(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
            'document' => 'required|file|mimes:pdf,txt,md,csv,json,docx,xlsx',
        ]);

        $prompt = $request->input('prompt');
        $document = $request->file('document');

        /** @var StructuredAgentResponse $response */
        $response = (new DocumentProcessorAgent)
            ->prompt(
                $prompt,
                attachments: [
                    $document,
                ]
            );

        return response()->json([
            'status' => 'Process document from file',
            'prompt' => $prompt,
            'filename' => $document->getClientOriginalName(),
            'response' => (string) $response,
        ]);
    }

    public function processDocumentFromStorage(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $prompt = $request->input('prompt');

        // -----------------------------------------------
        // Document::fromStorage reads from directory:
        // root\storage\app\private
        // -----------------------------------------------
        /** @var StructuredAgentResponse $response */
        $response = (new DocumentProcessorAgent)
            ->prompt(
                $prompt,
                attachments: [
                    Document::fromStorage('readme.txt'),
                ]
            );

        return response()->json([
            'status' => 'Process document from local storage',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }

    public function processImageFromRequest(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $prompt = $request->input('prompt');
        $imageFile = $request->file('image');

        /** @var StructuredAgentResponse $response */
        $response = (new ImageProcessorAgent)
            ->prompt(
                $prompt,
                attachments: [
                    $imageFile,
                ]
            );

        return response()->json([
            'status' => 'Process image from file',
            'prompt' => $prompt,
            'filename' => $imageFile->getClientOriginalName(),
            'response' => (string) $response,
        ]);
    }

    public function processImageFromUrl(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
            'url' => 'required|string|max:1000',
        ]);

        $prompt = $request->input('prompt');
        $imageUrl = $request->input('url');

        /** @var StructuredAgentResponse $response */
        $response = (new ImageProcessorAgent)
            ->prompt(
                $prompt,
                attachments: [
                    Image::fromUrl($imageUrl),
                ]
            );

        return response()->json([
            'status' => 'Process image from URL',
            'prompt' => $prompt,
            'image_url' => $imageUrl,
            'response' => (string) $response,
        ]);
    }
}
