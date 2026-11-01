<?php

namespace App\Models;

use App\Enums\MessageRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    /** @use HasFactory<\Database\Factories\ChatMessageFactory> */
    use HasFactory;

    protected $fillable = ['chat_session_id', 'role', 'content'];

    /**
     * Cast the role column to its enum.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['role' => MessageRole::class];
    }

    /**
     * The session this message belongs to.
     */
    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }

    /**
     * Tool calls triggered by this message.
     */
    public function toolExecutionLogs(): HasMany
    {
        return $this->hasMany(ToolExecutionLog::class);
    }
}
