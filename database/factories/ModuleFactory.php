<?php

namespace Database\Factories;

use App\Enums\ModuleStatus;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFactory extends Factory
{
    private array $productLines = [
        'SmartFactory Pro',
        'AgriTech Monitor',
        'UrbanSense',
        'IndustrialGuard',
        'EnviroTrack',
        'SafetyNet',
        'ClimateWatch',
        'UtilityGuard',
        'SmartCampus',
        'LogisticsSense',
    ];

    private array $descriptions = [
        'SmartFactory Pro' => 'Industrial IoT module with temperature, humidity, vibration and power consumption sensors for factory monitoring',
        'AgriTech Monitor' => 'Agricultural monitoring system with soil moisture, pH, temperature and light sensors for crop optimization',
        'UrbanSense' => 'Urban environment monitoring module with air quality, noise, traffic and weather sensors',
        'IndustrialGuard' => 'Safety and security module with gas detection, motion, smoke and pressure sensors for industrial facilities',
        'EnviroTrack' => 'Environmental monitoring system with water quality, air pollution, radiation and weather sensors',
        'SafetyNet' => 'Workplace safety module with proximity sensors, gas detectors, temperature and motion sensors',
        'ClimateWatch' => 'Climate control module with temperature, humidity, CO2 and air flow sensors for indoor spaces',
        'UtilityGuard' => 'Utility monitoring system with power, water, gas consumption and leak detection sensors',
        'SmartCampus' => 'Campus monitoring module with occupancy, air quality, noise and environmental sensors',
        'LogisticsSense' => 'Logistics monitoring system with location tracking, temperature, shock and humidity sensors',
    ];

    protected $model = Module::class;

    public function definition(): array
    {
        $productLine = fake()->randomElement($this->productLines);
        $status = fake()->randomElement(ModuleStatus::cases());
        $startedAt = fake()->dateTimeBetween('-1 year', '-6 month')->format('Y-m-d H:i:s');
        $stoppedAt = null;

        if ($status === ModuleStatus::DEACTIVATED) {
            $stoppedAt = fake()->dateTimeBetween($startedAt, '-1 day')->format('Y-m-d H:i:s');
        }

        return [
            'name' => $productLine.' v'.fake()->numerify('#.#'),
            'description' => $this->descriptions[$productLine].' (Serial: '.fake()->regexify('[A-Z]{2}[0-9]{6}').')',
            'status' => $status,
            'metric_count' => fake()->numberBetween(0, 1000000),
            'started_at' => $startedAt,
            'stopped_at' => $stoppedAt,
        ];
    }
}
