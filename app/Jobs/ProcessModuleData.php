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

    public function __construct(
        private int $moduleId,
        private array $readings
    ) {
        //
    }

    public function handle(): void
    {
        DB::transaction(function () {
            // Prepare readings data for upsert by mapping the input array
            // to the required database structure
            $readingsToUpsert = collect($this->readings)->map(fn ($reading) => [
                'sensor_id' => $reading['sensor_id'],
                'module_id' => $this->moduleId,
                'value' => $reading['value'],
                'recorded_at' => $reading['recorded_at'],
            ])->toArray();

            // Upsert all sensor readings using a composite unique key
            // Updates only the 'value' field if a record already exists
            SensorReading::upsert(
                $readingsToUpsert,
                ['sensor_id', 'module_id', 'recorded_at'], // Composite unique key
                ['value'] // Fields to update if record exists
            );

            // Update the current value for each sensor
            // This keeps track of the most recent reading for quick access
            collect($this->readings)->each(function ($reading) {
                Sensor::where('id', $reading['sensor_id'])
                    ->update(['current_value' => $reading['value']]);
            });

            // Update the module's status and total readings count
            $module = Module::find($this->moduleId);
            $module->status = ModuleStatus::OPERATIONAL;
            $module->metric_count = DB::raw('metric_count + '.count($this->readings));
            $module->save(); // Note: Calling save triggers the observer to clear cache
        });
    }
}
