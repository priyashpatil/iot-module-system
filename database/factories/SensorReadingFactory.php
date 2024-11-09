<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\Sensor;
use App\Simulation\SensorReadingSimulator;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorReadingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sensor_id' => Sensor::factory(),
            'module_id' => Module::factory(),
            ...SensorReadingSimulator::random(),
        ];
    }

    /**
     * Configure the factory to use existing sensor and module
     */
    public function forSensor(Sensor $sensor): self
    {
        return $this->state(fn (array $attributes) => [
            'sensor_id' => $sensor->id,
            'module_id' => $sensor->module_id,
            'value' => SensorReadingSimulator::generateRealisticValue($sensor->unit),
        ]);
    }

    /**
     * Configure the factory to use a specific time
     */
    public function atTime(\DateTimeInterface $time): self
    {
        return $this->state(fn (array $attributes) => [
            'recorded_at' => $time->format('Y-m-d H:i:s'),
        ]);
    }
}
