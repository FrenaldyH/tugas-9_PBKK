<?php

namespace Tests\Feature;

use App\Models\LogAnalyzer;
use App\Models\MalwareScanner;
use App\Models\SecurityScanner;
use App\Models\ToolExecutionLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PolymorphicLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{class-string, string}>
     */
    public static function agents(): array
    {
        return [
            'security scanner' => [SecurityScanner::class, 'security_scanner'],
            'malware scanner' => [MalwareScanner::class, 'malware_scanner'],
            'log analyzer' => [LogAnalyzer::class, 'log_analyzer'],
        ];
    }

    // loggable resolves to the right class and the column stores the short alias
    #[DataProvider('agents')]
    public function test_loggable_resolves_and_stores_the_morph_alias(string $class, string $alias): void
    {
        $agent = $class::factory()->create();
        $log = ToolExecutionLog::factory()->for($agent, 'loggable')->create();

        $this->assertDatabaseHas('tool_execution_logs', [
            'id' => $log->id,
            'loggable_type' => $alias,
            'loggable_id' => $agent->id,
        ]);
        $this->assertInstanceOf($class, $log->fresh()->loggable);
    }

    // The database cannot cascade on a morph column, so the trait does it
    #[DataProvider('agents')]
    public function test_deleting_an_agent_deletes_its_logs(string $class, string $alias): void
    {
        $agent = $class::factory()->create();
        ToolExecutionLog::factory(2)->for($agent, 'loggable')->create();
        $other = ToolExecutionLog::factory()->create();

        $agent->delete();

        $this->assertDatabaseMissing('tool_execution_logs', ['loggable_type' => $alias, 'loggable_id' => $agent->id]);
        $this->assertModelExists($other);
    }
}
