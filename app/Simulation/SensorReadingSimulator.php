<?php

namespace App\Simulation;

use Illuminate\Support\Carbon;

class SensorReadingSimulator
{
    /**
     * Get random sensor reading attributes
     */
    public static function random($unit = '°C'): array
    {
        return [
            'value' => self::generateRealisticValue($unit),
            'recorded_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Generate a realistic value based on the sensor unit
     */
    public static function generateRealisticValue(string $unit): float
    {
        return match ($unit) {
            '°C' => round(rand(-1000, 12000) / 100, 2),    // Temperature range (-10 to 120)
            'bar' => round(rand(0, 1000) / 100, 2),        // Pressure range (0 to 10)
            'rpm' => round(rand(0, 500000) / 100, 2),      // Motor speed range (0 to 5000)
            'kW' => round(rand(0, 50000) / 100, 2),        // Power range (0 to 500)
            '%' => round(rand(0, 10000) / 100, 2),         // Percentage range (0 to 100)
            default => round(rand(0, 10000) / 100, 2),     // Default range (0 to 100)
        };
    }
}
