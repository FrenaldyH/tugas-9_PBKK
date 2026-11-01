<?php

namespace App\Models;

use App\Enums\ToolStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ToolExecutionLog extends Model
{
    /** @use HasFactory<\Database\Factories\ToolExecutionLogFactory> */
    use HasFactory;

    protected $fillable = [
        'chat_message_id',
        'loggable_type',
        'loggable_id',
        'tool_name',
        'input',
        'result',
        'status',
        'duration_ms',
        'error_message',
    ];

    /**
     * Cast JSON columns to arrays and status to its enum.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'input' => 'array',
            'result' => 'array',
            'status' => ToolStatus::class,
        ];
    }

    /**
     * The message that triggered this tool call.
     */
    public function chatMessage(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class);
    }

    /**
     * The agent (SecurityScanner, MalwareScanner, LogAnalyzer) that ran the tool.
     */
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}
