<?php

namespace Database\Seeders;

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
        // One named agent per type; every tool log points at one of them (polymorphic)
        $agents = [
            'security_scanner' => SecurityScanner::factory()->create(['name' => 'Port & Vulnerability Scanner']),
            'malware_scanner' => MalwareScanner::factory()->create(['name' => 'Malware Scanner']),
            'log_analyzer' => LogAnalyzer::factory()->create(['name' => 'Log Analyzer']),
        ];

        $scenarios = ChatScenarios::all();

        // Demo user owns the fixed-size session ('audit') plus two more
        $demo = User::factory()->create(['name' => 'Demo User', 'email' => 'test@example.com']);
        foreach (['audit', 'ssl', 'weekly'] as $key) {
            $this->seedScenario($demo, $scenarios[$key], $agents);
        }

        // Two other users, so ownership (403 for other people's sessions) can be shown
        $rizky = User::factory()->create(['name' => 'Rizky Ramadhan', 'email' => 'rizky@example.com']);
        $this->seedScenario($rizky, $scenarios['traffic'], $agents);

        $sinta = User::factory()->create(['name' => 'Sinta Maharani', 'email' => 'sinta@example.com']);
        $this->seedScenario($sinta, $scenarios['antivirus'], $agents);
    }

    /**
     * Create one session with its messages and tool logs, using the factories.
     *
     * @param  array<string, mixed>  $scenario  one entry of ChatScenarios::all()
     * @param  array<string, mixed>  $agents  agent models keyed by morph alias
     */
    private function seedScenario(User $user, array $scenario, array $agents): void
    {
        $start = now()->subHours($scenario['hours_ago']);

        $session = ChatSession::factory()->for($user)->create([
            'title' => $scenario['title'],
            'created_at' => $start,
            'updated_at' => $start->copy()->addMinutes(count($scenario['messages'])),
        ]);

        foreach ($scenario['messages'] as $i => $message) {
            [$role, $content, $logs] = $message + [2 => []];

            // One minute apart, so the order stays stable
            $sentAt = $start->copy()->addMinutes($i);

            $chatMessage = ChatMessage::factory()->for($session)->create([
                'role' => $role,
                'content' => $content,
                'created_at' => $sentAt,
            ]);

            foreach ($logs as $log) {
                ToolExecutionLog::factory()
                    ->for($chatMessage, 'chatMessage')
                    ->for($agents[$log['agent']], 'loggable')
                    ->create([
                        'tool_name' => $log['tool'],
                        'input' => $log['input'],
                        'result' => $log['result'],
                        'status' => $log['status'],
                        'duration_ms' => $log['durationMs'],
                        'error_message' => $log['error'],
                        'created_at' => $sentAt,
                    ]);
            }
        }
    }
}
