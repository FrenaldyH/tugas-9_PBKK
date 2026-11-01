<?php

namespace Database\Seeders;

use App\Enums\MessageRole;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\LogAnalyzer;
use App\Models\MalwareScanner;
use App\Models\SecurityScanner;
use App\Models\ToolExecutionLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // One agent per type; every tool log points at one of them (polymorphic)
        $agents = [
            SecurityScanner::factory()->create(),
            MalwareScanner::factory()->create(),
            LogAnalyzer::factory()->create(),
        ];

        // Demo user plus four random users: 5 users x 3 sessions x about 8 messages
        $demo = User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);
        $users = User::factory(4)->create()->prepend($demo);

        foreach ($users as $user) {
            ChatSession::factory(3)->for($user)->create()->each(
                fn (ChatSession $session) => $this->fillSession($session, fake()->numberBetween(6, 10), $agents)
            );
        }

        // Fixed-size session (10 messages, 6 logs) so Debugbar before/after shots are comparable
        $fixed = ChatSession::factory()->for($demo)->create(['title' => 'Demo: 10 messages, 6 tool logs']);
        $this->fillSession($fixed, 10, $agents, [2, 1, 1, 1, 1]);
    }

    /**
     * Fill a session with alternating user/assistant messages.
     *
     * @param  array<int, mixed>  $agents  agent models used as the loggable
     * @param  array<int, int>|null  $logsPerReply  tool logs per assistant reply, random (0-2) when null
     */
    private function fillSession(ChatSession $session, int $messageCount, array $agents, ?array $logsPerReply = null): void
    {
        $start = now()->subHours(2);
        $reply = 0;

        for ($i = 0; $i < $messageCount; $i++) {
            $isUser = $i % 2 === 0;

            // Distinct timestamps keep the message order stable
            $message = ChatMessage::factory()->for($session)->create([
                'role' => $isUser ? MessageRole::User : MessageRole::Assistant,
                'created_at' => $start->copy()->addMinutes($i),
            ]);

            if ($isUser) {
                continue;
            }

            $logCount = $logsPerReply[$reply++] ?? fake()->numberBetween(0, 2);

            for ($n = 0; $n < $logCount; $n++) {
                ToolExecutionLog::factory()
                    ->for($message, 'chatMessage')
                    ->for(fake()->randomElement($agents), 'loggable')
                    ->create();
            }
        }
    }
}
