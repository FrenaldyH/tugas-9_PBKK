<?php

namespace App\Policies;

use App\Models\ChatSession;
use App\Models\User;

class ChatSessionPolicy
{
    /**
     * Any logged-in user may list sessions (the list itself is scoped to their own).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Only the owner may open a session.
     */
    public function view(User $user, ChatSession $chatSession): bool
    {
        return $user->id === $chatSession->user_id;
    }
}
