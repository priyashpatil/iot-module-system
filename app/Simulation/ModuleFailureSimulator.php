<?php

namespace App\Simulation;

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
            'failure_at' => self::generateRandomFailureDate(),
        ];
    }

    /**
     * Get a random failure description for the given error code
     */
    private static function getErrorCodeDescription(string $errorCode): string
    {
        return SimulationConfig::FAILURE_SCENARIOS[$errorCode][array_rand(SimulationConfig::FAILURE_SCENARIOS[$errorCode])];
    }

    /**
     * Generate a random failure date within the last 30 days
     */
    private static function generateRandomFailureDate(): \DateTime
    {
        return fake()->dateTimeBetween('-30 days', 'now');
    }
}
