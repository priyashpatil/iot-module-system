<?php

namespace Database\Seeders;

use App\Models\Sensor;
use App\Models\SensorReading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SensorReadingSeeder extends Seeder
{
    public function run(): void
    {
        $startDate = Carbon::now()->subMinutes(5);
        $endDate = Carbon::now();

        Sensor::all()->each(function (Sensor $sensor) use ($startDate, $endDate) {
            $readings = [];
            $currentTime = clone $startDate;

            // Generate readings for each second
            while ($currentTime <= $endDate) {
                $readings[] = SensorReading::factory()
                    ->forSensor($sensor)
                    ->atTime($currentTime)
                    ->make()
                    ->toArray();

                $currentTime->addSecond();

                // Insert in chunks to avoid memory issues
                if (count($readings) >= 5000) {
                    SensorReading::insert($readings);
                    $lastReading = end($readings);
                    $readings = []; // Clear the array after inserting
                }
            }

            // Insert any remaining readings
            if (! empty($readings)) {
                SensorReading::insert($readings);
                $lastReading = end($readings);
            }

            // Update sensor's current value using the last reading
            if (isset($lastReading)) {
                $sensor->update(['current_value' => $lastReading['value']]);
            }
        });
    }
}
