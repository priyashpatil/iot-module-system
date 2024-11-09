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
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(ModuleStatus::cases())->value,
            'active_since' => fake()->dateTimeBetween('-1 year', 'now'),
            'data_items_sent' => fake()->numberBetween(0, 1000000),
        ];
    }
}
