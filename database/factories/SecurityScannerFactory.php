<?php

namespace Database\Factories;

use App\Models\SecurityScanner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SecurityScanner>
 */
class SecurityScannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['name' => 'Security Scanner '.fake()->numberBetween(1, 999)];
    }
}
