<?php

namespace App\Simulation;

use App\Models\Module;
use App\Models\Sensor;
use Illuminate\Support\Collection;

class ModuleSimulator
{
    private Collection $modules;

    public function __construct()
    {
        $this->modules = new Collection;
    }

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

    public function addModule(Module $module): self
    {
        $this->modules->push($module);

        return $this;
    }

    private function shouldSimulateFailure(): bool
    {
        return rand(1, 100) <= SimulationConfig::FAILURE_PROBABILITY;
    }
}
