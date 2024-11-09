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

            // Update module status to MALFUNCTION and increment failure count
            Module::where('id', $this->failure['module_id'])
                ->update([
                    'status' => ModuleStatus::MALFUNCTION,
                    'failure_count' => DB::raw('failure_count + 1'),
                ]);

            Module::clearCache($this->failure['module_id']);
        });
    }
}
