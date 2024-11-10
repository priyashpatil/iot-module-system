<?php

namespace App\Observers;

use App\Models\Module;
use Illuminate\Support\Facades\Cache;

class ModuleObserver
{
    /**
     * Handle the Module "updated" event.
     *
     * Called when a Module model is updated. This clears any cached data
     * associated with the module to ensure fresh data is retrieved.
     *
     * @param  Module  $module  The Module model that was updated
     */
    public function updated(Module $module): void
    {
        Cache::forget("module_{$module->id}_transformed");
        Cache::forget("module_{$module->id}_data");
    }
}
