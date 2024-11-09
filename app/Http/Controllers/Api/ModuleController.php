<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Loads the module with:
     * - Latest 10 failures ordered by failure_at timestamp
     * - Latest 60 sensor readings for each sensor ordered by recorded_at timestamp
     *
     * @param  Request  $request  The incoming HTTP request
     * @param  Module  $module  The module model instance
     * @return ModuleResource Returns a JSON resource of the module with related data
     */
    public function __invoke(Module $module): ModuleResource
    {
        $module->load([
            'failures' => function ($query) {
                $query->latest('failure_at')->limit(10);
            },
            'sensors.readings' => function ($query) {
                $query->latest('recorded_at')->limit(60);
            },
        ]);

        return new ModuleResource($module);
    }
}
