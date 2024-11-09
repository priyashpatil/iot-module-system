<?php

namespace Database\Seeders;

use App\Enums\ModuleStatus;
use App\Models\Module;
use App\Models\ModuleFailure;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates test modules and their associated failure records:
     * - Generates 20 random test modules using the Module factory
     * - For modules with MALFUNCTION status:
     *   - Creates 3-6 random failure records
     *   - Sets failure timestamps within past 5 days
     */
    public function run(): void
    {
        Module::factory(20)
            ->create()
            ->each(function (Module $module) {
                // If the module is malfunctioning, create failure records
                if ($module->status === ModuleStatus::MALFUNCTION) {
                    $failureCount = rand(3, 6);

                    // Create 3-6 failure records for this module
                    ModuleFailure::factory($failureCount)->create([
                        'module_id' => $module->id,
                        // Set failure time to random point in last 5 days
                        'failure_at' => Carbon::now()
                            ->subDays(fake()->numberBetween(1, 5))
                            ->subHours(fake()->numberBetween(0, 23)),
                    ]);

                    // Update the failure count on the module
                    $module->update([
                        'failure_count' => $failureCount
                    ]);
                }
            });
    }
}
