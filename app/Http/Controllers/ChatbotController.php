<?php

namespace App\Http\Controllers;

use App\Services\EventBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChatbotController extends Controller
{
    protected EventBotService $eventBotService;

    public function __construct(EventBotService $eventBotService)
    {
        $this->eventBotService = $eventBotService;
    }

    /**
     * Handle incoming chat message
     */
    public function respond(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $request->input('message');
        $user = Auth::user();

        $result = $this->eventBotService->handleMessage($message, $user);

        return response()->json([
            'status' => 'success',
            'reply' => $result['reply'],
            'suggestions' => $result['suggestions'],
        ]);
    }

    /**
     * Clear chat session history
     */
    public function clearHistory(Request $request)
    {
        Session::forget('eventbot_history');

        return response()->json([
            'status' => 'success',
            'message' => 'Chat history cleared.',
            'suggestions' => $this->eventBotService->getDefaultSuggestions(),
        ]);
    }
}
