<?php

namespace App\Jobs;

use App\Enums\ModuleStatus;
use App\Models\Module;
use App\Models\Sensor;
use App\Models\SensorReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessModuleData implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $moduleId,
        private array $readings
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            // Prepare readings data for upsert
            $readingsToUpsert = collect($this->readings)->map(fn ($reading) => [
                'sensor_id' => $reading['sensor_id'],
                'module_id' => $this->moduleId,
                'value' => $reading['value'],
                'recorded_at' => $reading['recorded_at'],
            ])->toArray();

            // Upsert all sensor readings
            SensorReading::upsert(
                $readingsToUpsert,
                ['sensor_id', 'module_id', 'recorded_at'],
                ['value']
            );

            // Update current value for each sensor
            collect($this->readings)->each(function ($reading) {
                Sensor::where('id', $reading['sensor_id'])
                    ->update(['current_value' => $reading['value']]);
            });

            // Update module status and data_items_sent
            $totalReadings = SensorReading::where('module_id', $this->moduleId)->count();

            Module::where('id', $this->moduleId)->update([
                'status' => ModuleStatus::OPERATIONAL,
                'data_items_sent' => $totalReadings,
            ]);
        });
    }
}
