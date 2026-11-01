<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\ToolExecutionLog;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CascadeTest extends TestCase
{
    use RefreshDatabase;

    // Deleting a user must wipe every level below it
    public function test_deleting_a_user_cascades_to_all_descendants(): void
    {
        $user = User::factory()->create();
        $session = ChatSession::factory()->for($user)->create();
        $message = ChatMessage::factory()->for($session)->create();
        ToolExecutionLog::factory()->for($message, 'chatMessage')->create();

        // Another user's data must survive
        ToolExecutionLog::factory()->create();

        $user->delete();

        $this->assertDatabaseCount('chat_sessions', 1);
        $this->assertDatabaseCount('chat_messages', 1);
        $this->assertDatabaseCount('tool_execution_logs', 1);
        $this->assertDatabaseMissing('chat_sessions', ['id' => $session->id]);
        $this->assertDatabaseMissing('chat_messages', ['id' => $message->id]);
    }

    // Deleting a message removes only its own logs
    public function test_deleting_a_message_cascades_to_its_logs(): void
    {
        $message = ChatMessage::factory()->create();
        ToolExecutionLog::factory(2)->for($message, 'chatMessage')->create();

        $message->delete();

        $this->assertDatabaseCount('tool_execution_logs', 0);
    }

    // Proves the foreign key is enforced (also on SQLite)
    public function test_inserting_a_message_for_a_missing_session_fails(): void
    {
        $this->expectException(QueryException::class);

        ChatMessage::create([
            'chat_session_id' => 999999,
            'role' => 'user',
            'content' => 'orphan',
        ]);
    }
}
