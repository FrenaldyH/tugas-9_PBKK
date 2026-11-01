<?php

namespace Database\Factories;

use App\Models\LogAnalyzer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LogAnalyzer>
 */
class LogAnalyzerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['name' => 'Log Analyzer '.fake()->numberBetween(1, 999)];
    }
}
