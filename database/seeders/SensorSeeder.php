<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Sensor;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
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
