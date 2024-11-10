<?php

namespace App\Http\Controllers;

use App\Enums\ModuleStatus;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ModuleController extends Controller
{
    /**
     * Display the specified module
     *
     * @param  Module  $module  The module instance to show
     */
    public function show(Module $module): View
    {
        return view('modules.show', compact('module'));
    }

    /**
     * Store a newly created module in database
     *
     * @param  Request  $request  The incoming request containing module data
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'sensors' => 'required|array|min:1',
            'sensors.*.name' => 'required|string|max:255',
            'sensors.*.unit' => 'required|string|max:50',
        ]);

        $module = Module::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'started_at' => Carbon::now(),
        ]);

        foreach ($validated['sensors'] as $sensorData) {
            $module->sensors()->create([
                'name' => $sensorData['name'],
                'unit' => $sensorData['unit'],
            ]);
        }

        return redirect()->route('modules.show', $module->id)
            ->with(['alert' => [
                'message' => 'Module Created Successfully',
                'type' => 'success',
            ]], 201);
    }

    /**
     * Toggle the operational status of the specified module
     *
     * @param  Module  $module  The module instance to toggle
     */
    public function toggle(Module $module): RedirectResponse
    {
        $isDeactivated = $module->status === ModuleStatus::DEACTIVATED;

        $module->update([
            'status' => $isDeactivated ? ModuleStatus::OPERATIONAL : ModuleStatus::DEACTIVATED,
            $isDeactivated ? 'started_at' : 'stopped_at' => Carbon::now(),
        ]);

        return redirect()->route('modules.show', $module->id)
            ->with(['alert' => [
                'message' => $isDeactivated ? 'Module Activated Successfully' : 'Module Deactivated Successfully',
                'type' => 'success',
            ]], 200);
    }
}
