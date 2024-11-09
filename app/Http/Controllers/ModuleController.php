<?php

namespace App\Http\Controllers;

use App\Enums\ModuleStatus;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ModuleController extends Controller
{
    public function show(Module $module)
    {
        return view('modules.show', compact('module'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'sensors' => 'required|array|min:1',
            'sensors.*.name' => 'required|string|max:255',
            'sensors.*.unit' => 'required|string|max:50'
        ]);

        $module = Module::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'started_at' => Carbon::now()
        ]);

        foreach ($validated['sensors'] as $sensorData) {
            $module->sensors()->create([
                'name' => $sensorData['name'],
                'unit' => $sensorData['unit']
            ]);
        }

        return redirect()->route('modules.show', $module->id)
            ->with(['alert' => [
                'message' => 'Module Created Successfully',
                'type' => 'success',
            ]], 201);
    }

    public function activate(Module $module)
    {
        $module->update([
            'status' => ModuleStatus::OPERATIONAL,
            'started_at' => Carbon::now(),
        ]);

        return redirect()->route('modules.show', $module->id)
            ->with(['alert' => [
                'message' => 'Module Activated Successfully',
                'type' => 'success',
            ]], 200);
    }

    public function deactivate(Module $module)
    {
        $module->update([
            'status' => ModuleStatus::DEACTIVATED,
            'stopped_at' => Carbon::now(),
        ]);

        return redirect()->route('modules.show', $module->id)
            ->with(['alert' => [
                'message' => 'Module Deactivated Successfully',
                'type' => 'success',
            ]], 200);
    }
}
