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
            'message' => 'required|string|max:1000'
        ]);

        $user = $request->user();
        $message = $request->input('message');

        $response = (new ConversationalAgent)
        ->forUser($user)
        ->prompt($message);

        return response()->json([
            'status' => 'Start conversation (RemembersConversations)',
            'conversation_id' => $response->conversationId,
            'user_message' => $message,
            'assistant_response' => (string) $response,
            'hint' => 'Save the conversation_id!'
        ]);
    }

    public function continueConversation(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|string',
            'message' => 'required|string|max:1000'
        ]);

        $user = $request->user();
        $message = $request->input('message');
        $conversationId = $request->input('conversation_id');

        $response = (new ConversationalAgent)
        ->continue($conversationId, as: $user)
        ->prompt($message);

        return response()->json([
            'status' => 'Continue conversation (RemembersConversations)',
            'conversation_id' => $response->conversationId,
            'user_message' => $message,
            'assistant_response' => (string) $response,
        ]);
    }
}
