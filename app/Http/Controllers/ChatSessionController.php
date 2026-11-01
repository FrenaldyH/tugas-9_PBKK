<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ChatSessionController extends Controller
{
    /**
     * List the logged-in user's sessions, newest first.
     */
    public function index(Request $request): View
    {
        // withCount adds the message total in the same query, no per-row count
        $chatSessions = $request->user()
            ->chatSessions()
            ->withCount('messages')
            ->latest('updated_at')
            ->get();

        return view('chat-sessions.index', compact('chatSessions'));
    }

    /**
     * Show one session with all messages and their tool logs.
     */
    public function show(ChatSession $chatSession): View
    {
        // Only the owner may open a session (ChatSessionPolicy@view)
        Gate::authorize('view', $chatSession);

        // Nested eager loading: one query per level, not one per row
        $chatSession->load([
            'messages' => fn ($query) => $query->orderBy('created_at')->orderBy('id'),
            'messages.toolExecutionLogs.loggable',
        ]);

        return view('chat-sessions.show', compact('chatSession'));
    }
}
