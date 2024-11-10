<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\SensorReading;
use Illuminate\Http\Request;

class ModuleLogsController extends Controller
{
    public function show(Request $request, Module $module)
    {
        $sensors = $module->sensors()->get(['id', 'name']);

        $readings = SensorReading::where('module_id', $module->id)
            ->when(
                $request->filled('sensor'),
                fn ($query) => $query->where('sensor_id', $request->get('sensor'))
            )
            ->with(['sensor:id,name,unit'])
            ->latest('recorded_at')
            ->paginate(50);

        return view('modules.logs', compact('module', 'sensors', 'readings'));
    }
}
