<?php

namespace Tests\Feature;

use App\Enums\MessageRole;
use App\Enums\ToolStatus;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\SecurityScanner;
use App\Models\ToolExecutionLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationsTest extends TestCase
{
    use RefreshDatabase;

    // Each relation method returns the right type and points at the right rows
    public function test_every_relation_returns_the_right_type_and_data(): void
    {
        $user = User::factory()->create();
        $session = ChatSession::factory()->for($user)->create();
        $message = ChatMessage::factory()->for($session)->create();
        $agent = SecurityScanner::factory()->create();
        $log = ToolExecutionLog::factory()->for($message, 'chatMessage')->for($agent, 'loggable')->create();

        // hasMany sides
        $this->assertInstanceOf(HasMany::class, $user->chatSessions());
        $this->assertTrue($user->chatSessions->first()->is($session));
        $this->assertInstanceOf(HasMany::class, $session->messages());
        $this->assertTrue($session->messages->first()->is($message));
        $this->assertInstanceOf(HasMany::class, $message->toolExecutionLogs());
        $this->assertTrue($message->toolExecutionLogs->first()->is($log));

        // belongsTo sides
        $this->assertInstanceOf(BelongsTo::class, $session->user());
        $this->assertTrue($session->user->is($user));
        $this->assertInstanceOf(BelongsTo::class, $message->chatSession());
        $this->assertTrue($message->chatSession->is($session));
        $this->assertInstanceOf(BelongsTo::class, $log->chatMessage());
        $this->assertTrue($log->chatMessage->is($message));

        // polymorphic sides
        $this->assertInstanceOf(MorphTo::class, $log->loggable());
        $this->assertInstanceOf(MorphMany::class, $agent->toolExecutionLogs());
        $this->assertTrue($agent->toolExecutionLogs->first()->is($log));
    }

    // role and status come back as enums, input and result as arrays
    public function test_casts_are_applied(): void
    {
        $log = ToolExecutionLog::factory()->create([
            'status' => ToolStatus::Success,
            'input' => ['target' => 'example.com'],
        ])->fresh();
        $message = ChatMessage::factory()->create(['role' => MessageRole::Assistant])->fresh();

        $this->assertSame(ToolStatus::Success, $log->status);
        $this->assertSame(['target' => 'example.com'], $log->input);
        $this->assertSame(MessageRole::Assistant, $message->role);
    }
}
