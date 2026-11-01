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

        // Kelompok 1 members as demo accounts (factory password: "password")
        $members = collect([
            'frenaldy' => 'Frenaldy Bestabba Hasugian',
            'mitra' => 'Mitra Partogi',
            'dimas' => 'Dimas Setiaji',
            'steven' => 'Steven Alvin Christian',
            'hoshida' => 'Hoshida Ahmad Tsalatsu Fadlulloh',
            'isabella' => 'Isabella Sienna Sulisthio',
        ])->map(fn (string $name, string $key) => User::factory()->create([
            'name' => $name,
            'email' => "{$key}@example.com",
        ]));

        // Who owns which scenario. Frenaldy holds the fixed-size 'audit' session; sessions of other
        // members show the 403 check, and members without a session show the empty state
        $owners = [
            'audit' => 'frenaldy',
            'ssl' => 'frenaldy',
            'weekly' => 'frenaldy',
            'traffic' => 'mitra',
            'antivirus' => 'dimas',
        ];

        foreach ($owners as $scenario => $member) {
            $this->seedScenario($members[$member], $scenarios[$scenario], $agents);
        }
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
