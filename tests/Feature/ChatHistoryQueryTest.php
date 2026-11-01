<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\LogAnalyzer;
use App\Models\MalwareScanner;
use App\Models\SecurityScanner;
use App\Models\ToolExecutionLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChatHistoryQueryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Build a session where every message has one log per agent type.
     */
    private function sessionWithMessages(User $user, int $messageCount): ChatSession
    {
        $session = ChatSession::factory()->for($user)->create();
        $agents = [SecurityScanner::factory()->create(), MalwareScanner::factory()->create(), LogAnalyzer::factory()->create()];

        ChatMessage::factory($messageCount)->for($session)->create()->each(function (ChatMessage $message) use ($agents) {
            foreach ($agents as $agent) {
                ToolExecutionLog::factory()->for($message, 'chatMessage')->for($agent, 'loggable')->create();
            }
        });

        return $session;
    }

    /**
     * Number of queries run while opening the session page.
     */
    private function queryCountFor(User $user, ChatSession $session): int
    {
        DB::enableQueryLog();
        $this->actingAs($user)->get(route('chat-sessions.show', $session))->assertOk();
        $count = count(DB::getQueryLog());
        DB::flushQueryLog();

        return $count;
    }

    // The core N+1 proof: 10x more messages and logs, the same number of queries
    public function test_query_count_does_not_grow_with_the_amount_of_data(): void
    {
        $user = User::factory()->create();
        $small = $this->sessionWithMessages($user, 2);
        $large = $this->sessionWithMessages($user, 20);

        $smallCount = $this->queryCountFor($user, $small);
        $largeCount = $this->queryCountFor($user, $large);

        $this->assertSame($smallCount, $largeCount);
        // session + messages + logs + one query per agent type (3)
        $this->assertLessThanOrEqual(7, $largeCount);
    }

    public function test_owner_sees_messages_and_tool_logs(): void
    {
        $user = User::factory()->create();
        $session = $this->sessionWithMessages($user, 2);

        $this->actingAs($user)
            ->get(route('chat-sessions.show', $session))
            ->assertOk()
            ->assertSee('security_scanner')
            ->assertSee('log_analyzer');
    }

    public function test_another_user_gets_403(): void
    {
        $session = $this->sessionWithMessages(User::factory()->create(), 1);

        $this->actingAs(User::factory()->create())
            ->get(route('chat-sessions.show', $session))
            ->assertForbidden();
    }

    public function test_index_lists_only_own_sessions(): void
    {
        $user = User::factory()->create();
        ChatSession::factory()->for($user)->create(['title' => 'Mine']);
        ChatSession::factory()->create(['title' => 'Someone else']);

        $this->actingAs($user)
            ->get(route('chat-sessions.index'))
            ->assertOk()
            ->assertSee('Mine')
            ->assertDontSee('Someone else');
    }
}
