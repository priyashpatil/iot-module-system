<?php

namespace App\Simulation;

use Illuminate\Support\Carbon;

class ModuleFailureSimulator
{
    /**
     * Get random failure attributes
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
     * Get a random failure description for the given error code
     */
    private static function getErrorCodeDescription(string $errorCode): string
    {
        return SimulationConfig::FAILURE_SCENARIOS[$errorCode][array_rand(SimulationConfig::FAILURE_SCENARIOS[$errorCode])];
    }
}
