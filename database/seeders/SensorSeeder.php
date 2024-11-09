<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Sensor;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Seeds the sensors table with random sensor records for each module.
     *
     * Creates between 3-6 sensor records for each module in the system using the SensorFactory.
     * Each sensor is associated with a module via the module_id foreign key.
     */
    public function run(): void
    {
        Module::all()->each(function (Module $module) {
            Sensor::factory(rand(3, 6))
                ->create([
                    'module_id' => $module->id,
                ]);
        });
    }
}
