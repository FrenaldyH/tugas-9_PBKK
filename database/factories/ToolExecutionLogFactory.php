<?php

namespace Database\Factories;

use App\Enums\ToolStatus;
use App\Models\ChatMessage;
use App\Models\LogAnalyzer;
use App\Models\MalwareScanner;
use App\Models\SecurityScanner;
use App\Models\ToolExecutionLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ToolExecutionLog>
 */
class ToolExecutionLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pick one of the three agent types at random, so dummy data covers all of them
        $agent = fake()->randomElement([
            SecurityScanner::class,
            MalwareScanner::class,
            LogAnalyzer::class,
        ]);

        return [
            'chat_message_id' => ChatMessage::factory(),
            'loggable_type' => (new $agent())->getMorphClass(),
            'loggable_id' => $agent::factory(),
            'tool_name' => fake()->randomElement([
                'search_files', 'scan_ports', 'analyze_logs', 'check_hash', 'read_file',
            ]),
            'input' => ['target' => fake()->domainName(), 'depth' => fake()->numberBetween(1, 5)],
            'status' => fake()->randomElement(ToolStatus::cases()),
            // Fields below depend on the status that was just picked
            'result' => fn (array $log) => $log['status'] === ToolStatus::Success
                ? ['matches' => fake()->numberBetween(0, 20)]
                : null,
            'duration_ms' => fn (array $log) => $log['status'] === ToolStatus::Pending
                ? null
                : fake()->numberBetween(20, 4000),
            'error_message' => fn (array $log) => $log['status'] === ToolStatus::Failed
                ? fake()->sentence()
                : null,
        ];
    }
}
