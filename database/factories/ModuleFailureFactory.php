<?php

namespace Database\Factories;

use App\Enums\ModuleStatus;
use App\Models\Module;
use App\Models\ModuleFailure;
use App\Simulation\SimulationConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFailureFactory extends Factory
{
    protected $model = ModuleFailure::class;

    public function definition(): array
    {
        $errorCode = fake()->randomElement(array_keys(SimulationConfig::FAILURE_SCENARIOS));

        return [
            'module_id' => Module::factory()->state([
                'status' => ModuleStatus::MALFUNCTION,
            ]),
            'failure_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'description' => fake()->randomElement(SimulationConfig::FAILURE_SCENARIOS[$errorCode]),
            'error_code' => $errorCode,
        ];
    }
}
