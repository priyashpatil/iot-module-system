<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Module $module)
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
