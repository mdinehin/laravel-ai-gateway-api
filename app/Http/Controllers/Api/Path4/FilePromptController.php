<?php

namespace App\Http\Controllers\Api\Path4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Ai\Responses\StructuredAgentResponse;
use App\Ai\Agents\DocumentProcessorAgent;
use Laravel\Ai\Files\Document;

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
            'status' => 'File Prompt (From API Request)',
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
            'status' => 'File Prompt (From Local Storage)',
            'prompt' => $prompt,
            'response' => (string) $response,
        ]);
    }
}
