<?php

namespace App\Console\Commands;

use App\Enums\ModuleStatus;
use App\Jobs\ProcessModuleData;
use App\Jobs\ProcessModuleFailure;
use App\Models\Module;
use App\Simulation\ModuleSimulator;
use Illuminate\Console\Command;

class SimulateModules extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'modules:simulate
                          {--modules=* : Specific module IDs to simulate}
                          {--interval=5 : Interval between simulations in seconds}
                          {--limit=100 : Maximum number of modules to simulate}
                          {--failure=15 : Optional failure probability percentage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate module operation and sensor readings';

    /**
     * Execute the console command.
     *
     * This method:
     * 1. Loads the specified modules (or all modules if none specified)
     * 2. Initializes the simulator
     * 3. Runs continuous simulation at specified intervals
     * 4. Processes generated sensor readings and failures
     *
     * @return int Command exit code (0: success, 1: failure)
     */
    public function handle(): int
    {
        $interval = $this->option('interval');
        $moduleIds = $this->option('modules');
        $limit = $this->option('limit');
        $failureProbability = $this->option('failure');

        // Load modules to simulate
        $query = Module::with(['sensors:id,module_id,unit'])
            ->select(['id', 'status'])
            ->where('status', '!=', ModuleStatus::DEACTIVATED->value);

        if (! empty($moduleIds)) {
            $query->whereIn('id', $moduleIds);
        }

        $modules = $query->limit($limit)->get();

        if ($modules->isEmpty()) {
            $this->error('No active modules found to simulate');

            return 1;
        }

        $simulator = new ModuleSimulator($failureProbability);
        $modules->each(fn ($module) => $simulator->addModule($module));

        $this->info("Starting simulation for {$modules->count()} modules");
        $this->info('Press Ctrl+C to stop the simulation');

        // Infinite loop for continuous simulation
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

            // Process any failures that occurred during simulation
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
