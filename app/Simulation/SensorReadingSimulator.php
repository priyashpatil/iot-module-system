<?php

namespace App\Simulation;

class SensorReadingSimulator
{
    /**
     * Get random sensor reading attributes
     */
    public static function random($unit = '°C'): array
    {
        return [
            'value' => self::generateRealisticValue($unit),
            'recorded_at' => self::generateRandomRecordingDate(),
        ];
    }

    /**
     * Generate a realistic value based on the sensor unit
     */
    public static function generateRealisticValue(string $unit): float
    {
        return match ($unit) {
            '°C' => fake()->randomFloat(2, -10, 120),    // Temperature range
            'bar' => fake()->randomFloat(2, 0, 10),      // Pressure range
            'rpm' => fake()->randomFloat(2, 0, 5000),    // Motor speed range
            'kW' => fake()->randomFloat(2, 0, 500),      // Power range
            '%' => fake()->randomFloat(2, 0, 100),       // Percentage range
            default => fake()->randomFloat(2, 0, 100),   // Default range
        };
    }

    /**
     * Generate a random recording date within the last 30 days
     */
    private static function generateRandomRecordingDate(): string
    {
        return fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d H:i:s');
    }
}
