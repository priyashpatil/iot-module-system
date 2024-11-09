<?php

namespace Database\Seeders;

use App\Models\Sensor;
use App\Models\SensorReading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SensorReadingSeeder extends Seeder
{
    /**
     * Seed the sensor readings table with simulated data.
     *
     * This seeder generates sensor readings for each sensor in 1-second intervals
     * over the last 5 minutes. The readings are inserted in chunks to avoid memory issues.
     * After seeding, each sensor's current_value is updated to match its latest reading.
     */
    public function run(): void
    {
        // Set time range for data generation - last 5 minutes until now
        $startDate = Carbon::now()->subMinutes(5);
        $endDate = Carbon::now();

        Sensor::all()->each(function (Sensor $sensor) use ($startDate, $endDate) {
            $readings = [];
            $currentTime = clone $startDate;

            // Generate readings for each second within the time range
            while ($currentTime <= $endDate) {
                $readings[] = SensorReading::factory()
                    ->forSensor($sensor)
                    ->atTime($currentTime)
                    ->make()
                    ->toArray();

                $currentTime->addSecond();

                // Insert in chunks of 5000 to avoid memory issues
                if (count($readings) >= 5000) {
                    SensorReading::insert($readings);
                    $lastReading = end($readings);
                    $readings = []; // Clear the array after inserting
                }
            }

            // Insert any remaining readings that didn't make a full chunk
            if (! empty($readings)) {
                SensorReading::insert($readings);
                $lastReading = end($readings);
            }

            // Update the sensor's current value to match its most recent reading
            if (isset($lastReading)) {
                $sensor->update(['current_value' => $lastReading['value']]);
            }
        });
    }
}
