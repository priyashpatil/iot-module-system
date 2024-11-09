<?php

namespace App\Console\Commands;

use App\Jobs\ProcessModuleData;
use App\Jobs\ProcessModuleFailure;
use App\Models\Module;
use App\Simulation\ModuleSimulator;
use Illuminate\Console\Command;

class SimulateModules extends Command
{
    protected $signature = 'modules:simulate
                          {--interval=5 : Interval between simulations in seconds}
                          {--modules=* : Specific module IDs to simulate}';

    protected $description = 'Simulate module operation and sensor readings';

    public function handle(): int
    {
        $interval = $this->option('interval');
        $moduleIds = $this->option('modules');

        // Load modules to simulate
        $query = Module::with('sensors');
        if (! empty($moduleIds)) {
            $query->whereIn('id', $moduleIds);
        }

        $modules = $query->get();

        if ($modules->isEmpty()) {
            $this->error('No modules found to simulate');

            return 1;
        }

        $simulator = new ModuleSimulator;
        $modules->each(fn ($module) => $simulator->addModule($module));

        $this->info("Starting simulation for {$modules->count()} modules");
        $this->info('Press Ctrl+C to stop the simulation');

        while (true) {
            $this->info('Simulating... '.now()->toDateTimeString());

            $simulatedData = $simulator->simulate();

            // Process sensor readings grouped by module
            if (! empty($simulatedData['sensor_readings'])) {
                $groupedReadings = collect($simulatedData['sensor_readings'])
                    ->groupBy('module_id')
                    ->toArray();

                foreach ($groupedReadings as $moduleId => $readings) {
                    ProcessModuleData::dispatch($moduleId, $readings);
                }
            }

            // Process failures
            if (! empty($simulatedData['failures'])) {
                foreach ($simulatedData['failures'] as $failure) {
                    ProcessModuleFailure::dispatch($failure);
                }
            }

            sleep($interval);
        }

        return 0;
    }
}
