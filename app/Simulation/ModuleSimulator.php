<?php

namespace App\Simulation;

use App\Models\Module;
use App\Models\Sensor;
use Illuminate\Support\Collection;

class ModuleSimulator
{
    private Collection $modules;

    private int $failureProbability;

    public function __construct(int $failureProbability)
    {
        $this->modules = new Collection;
        $this->failureProbability = $failureProbability;
    }

    /**
     * Simulates sensor readings and failures for all registered modules.
     *
     * This method iterates through each registered module and:
     * 1. Checks if the module should experience a failure
     * 2. If no failure, simulates readings for all sensors in the module
     *
     * @return array{
     *     sensor_readings: array<array{
     *         sensor_id: int,
     *         module_id: int,
     *         mixed,
     *     }>,
     *     failures: array<array{
     *         module_id: int,
     *         mixed,
     *     }>,
     * } Array containing simulated sensor readings and module failures
     */
    public function simulate(): array
    {
        $simulationData = [
            'sensor_readings' => [],
            'failures' => [],
        ];

        $this->modules->each(function (Module $module) use (&$simulationData) {
            // Check for failures first
            if ($this->shouldSimulateFailure()) {
                $failure = ModuleFailureSimulator::random();

                $simulationData['failures'][] = [
                    'module_id' => $module->id,
                    ...$failure,
                ];

                // Skip sensor readings for failed modules
                return;
            }

            // Only simulate readings if module hasn't failed
            $module->sensors->each(function (Sensor $sensor) use ($module, &$simulationData) {
                $reading = SensorReadingSimulator::random($sensor->unit);

                $simulationData['sensor_readings'][] = [
                    'sensor_id' => $sensor->id,
                    'module_id' => $module->id,
                    ...$reading,
                ];
            });
        });

        return $simulationData;
    }

    /**
     * Adds a module to the simulation.
     *
     * @param  Module  $module  The module to add to the simulation
     * @return self Returns the simulator instance for method chaining
     */
    public function addModule(Module $module): self
    {
        $this->modules->push($module);

        return $this;
    }

    /**
     * Determines if a module should experience a failure during simulation.
     *
     * Uses the failure probability set in the constructor to determine
     * the likelihood of failure.
     *
     * @return bool True if the module should fail, false otherwise
     */
    private function shouldSimulateFailure(): bool
    {
        return rand(1, 100) <= $this->failureProbability;
    }
}
