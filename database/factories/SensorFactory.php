<?php

namespace Database\Factories;

use App\Models\Module;
use App\Simulation\SimulationConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorFactory extends Factory
{
    public function definition(): array
    {
        return [
            ...self::random(),
            'module_id' => Module::factory(),
        ];
    }

    /**
     * Get random sensor attributes for any sensor type
     */
    public static function random(): array
    {
        $sensorType = array_rand(SimulationConfig::SENSOR_TYPES);
        $config = SimulationConfig::SENSOR_TYPES[$sensorType];

        return [
            'name' => self::getRandomName($config),
            'unit' => $config['unit'],
            'current_value' => self::generateRandomValue($config['min'], $config['max']),
        ];
    }

    /**
     * Get a random name from the sensor config
     */
    private static function getRandomName(array $config): string
    {
        return $config['names'][array_rand($config['names'])];
    }

    /**
     * Generate a random value between min and max with 2 decimal places
     */
    private static function generateRandomValue(float $min, float $max): float
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), 2);
    }
}
