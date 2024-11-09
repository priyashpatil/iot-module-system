<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:10',
        ]);

        $module->sensors()->create($validated);

        return redirect()->back()
            ->with(['alert' => [
                'message' => 'Sensor Added Successfully',
                'type' => 'success',
            ]], 201);
    }
}
