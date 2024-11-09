<?php

namespace App\Simulation;

use Illuminate\Support\Carbon;

class ModuleFailureSimulator
{
    /**
     * Generate random failure details with error code, description and timestamp
     *
     * @return array<string, mixed> Array of failure details
     */
    public static function random(): array
    {
        $errorCode = array_rand(SimulationConfig::FAILURE_SCENARIOS);

        return [
            'error_code' => $errorCode,
            'description' => self::getErrorCodeDescription($errorCode),
            'failure_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get a random failure description for the given error code.
     *
     * Randomly selects one description from the array of descriptions defined
     * for the error code in SimulationConfig::FAILURE_SCENARIOS.
     *
     * @param  string  $errorCode  The error code to get a description for
     * @return string Random description text for the error code
     */
    private static function getErrorCodeDescription(string $errorCode): string
    {
        return SimulationConfig::FAILURE_SCENARIOS[$errorCode][array_rand(SimulationConfig::FAILURE_SCENARIOS[$errorCode])];
    }
}
