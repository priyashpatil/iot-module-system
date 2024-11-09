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

    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $failure
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            // Update module status to MALFUNCTION
            $module = Module::findOrFail($this->failure['module_id']);
            $module->update([
                'status' => ModuleStatus::MALFUNCTION,
            ]);

            // Create module failure record
            ModuleFailure::create([
                'module_id' => $this->failure['module_id'],
                'error_code' => $this->failure['error_code'],
                'description' => $this->failure['description'],
                'failure_at' => $this->failure['failure_at'],
            ]);
        });
    }
}
