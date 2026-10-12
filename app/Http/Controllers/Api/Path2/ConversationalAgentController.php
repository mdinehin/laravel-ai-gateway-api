<?php

namespace App\Http\Controllers\Api\Path2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Ai\Agents\ConversationalAgent;

class ConversationalAgentController extends Controller
{
    public function startConversation(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        $inputText = $request->input('text');

        $response = (new ConversationalAgent)
        ->forUser($user)
        ->prompt($inputText);

        return response()->json([
            'status' => 'Start conversation (RemembersConversations)',
            'conversation_id' => $response->conversationId,
            'input_text' => $inputText,
            'response' => (string) $response,
            'hint' => 'Save the conversation_id!',
        ]);
    }

    public function continueConversation(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|string',
            'text' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        $inputText = $request->input('text');
        $conversationId = $request->input('conversation_id');

        $response = (new ConversationalAgent)
        ->continue($conversationId, as: $user)
        ->prompt($inputText);

        return response()->json([
            'status' => 'Continue conversation (RemembersConversations)',
            'conversation_id' => $response->conversationId,
            'input_text' => $inputText,
            'response' => (string) $response,
        ]);
    }
}
