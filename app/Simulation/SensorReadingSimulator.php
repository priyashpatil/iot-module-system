<?php

namespace App\Simulation;

use Illuminate\Support\Carbon;

class SensorReadingSimulator
{
    /**
     * Generate random sensor reading data with timestamp
     *
     * Creates an array containing a simulated sensor reading value and the current timestamp.
     * The sensor value is generated based on the specified unit of measurement.
     *
     * @param  string  $unit  The unit of measurement (e.g., '°C', 'bar', 'rpm', 'kW', '%')
     * @return array Contains 'value' (float) and 'recorded_at' (timestamp string)
     */
    public static function random($unit = '°C'): array
    {
        return [
            'value' => self::generateRealisticValue($unit),
            'recorded_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Generate a realistic sensor value based on the unit of measurement
     *
     * Produces simulated sensor readings within realistic ranges for different types of measurements:
     * - Temperature (°C): -10°C to 120°C
     * - Pressure (bar): 0 to 10 bar
     * - Motor speed (rpm): 0 to 5000 rpm
     * - Power (kW): 0 to 500 kW
     * - Percentage (%): 0% to 100%
     * - Default: 0 to 100
     *
     * @param  string  $unit  The unit of measurement to determine the value range
     * @return float The generated sensor reading value with 2 decimal places
     */
    public static function generateRealisticValue(string $unit): float
    {
        return match ($unit) {
            // Using -1000 (divided by 100) instead of -10 to ensure 2 decimal places in the result
            // -1000/100 = -10.00, while -10 would give -10.0
            '°C' => round(rand(-1000, 12000) / 100, 2),
            'bar' => round(rand(0, 1000) / 100, 2),
            'rpm' => round(rand(0, 500000) / 100, 2),
            'kW' => round(rand(0, 50000) / 100, 2),
            '%' => round(rand(0, 10000) / 100, 2),
            default => round(rand(0, 10000) / 100, 2),
        };
    }
}
