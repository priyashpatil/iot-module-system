<?php

namespace App\Jobs;

use App\Enums\ModuleStatus;
use App\Models\Module;
use App\Models\ModuleFailure;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessModuleFailure implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private array $failure
    ) {
        //
    }

    public function handle(): void
    {
        DB::transaction(function () {
            // Create module failure record
            ModuleFailure::create([
                'module_id' => $this->failure['module_id'],
                'error_code' => $this->failure['error_code'],
                'description' => $this->failure['description'],
                'failure_at' => $this->failure['failure_at'],
            ]);

            // Update the module status and increment the failure count
            $module = Module::find($this->failure['module_id']);
            $module->status = ModuleStatus::MALFUNCTION;
            $module->failure_count = DB::raw('failure_count + 1');
            $module->save(); // Note: Calling save triggers the observer to clear cache
        });
    }
}
