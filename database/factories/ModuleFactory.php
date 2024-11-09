<?php

namespace Database\Factories;

use App\Enums\ModuleStatus;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFactory extends Factory
{
    protected $model = Module::class;

    public function definition(): array
    {
        $status = fake()->randomElement(ModuleStatus::cases());
        $startedAt = fake()->dateTimeBetween('-1 year', '-6 month')->format('Y-m-d H:i:s');
        $stoppedAt = null;

        if ($status === ModuleStatus::DEACTIVATED) {
            $stoppedAt = fake()->dateTimeBetween($startedAt, '-1 day')->format('Y-m-d H:i:s');
        }

        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'status' => $status,
            'data_items_sent' => fake()->numberBetween(0, 1000000),
            'started_at' => $startedAt,
            'stopped_at' => $stoppedAt,
        ];
    }
}
