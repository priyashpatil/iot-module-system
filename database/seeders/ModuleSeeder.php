<?php

namespace Database\Seeders;

use App\Enums\ModuleStatus;
use App\Models\Module;
use App\Models\ModuleFailure;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        Module::factory(20)
            ->sequence(fn ($sequence) => [
                'status' => match (true) {
                    $sequence->index < 15 => ModuleStatus::OPERATIONAL->value,
                    $sequence->index < 18 => ModuleStatus::MALFUNCTION->value,
                    default => ModuleStatus::DEACTIVATED->value,
                },
            ])
            ->create()
            ->each(function (Module $module) {
                if ($module->status === ModuleStatus::MALFUNCTION) {
                    ModuleFailure::factory(rand(3, 6))->create([
                        'module_id' => $module->id,
                        'failure_at' => Carbon::now()
                            ->subDays(fake()->numberBetween(1, 5))
                            ->subHours(fake()->numberBetween(0, 23)),
                    ]);
                }
            });
    }
}
