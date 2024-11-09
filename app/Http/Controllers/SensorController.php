<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Store a newly created sensor in the database
     *
     * @param  Request  $request  The HTTP request containing sensor data
     * @param  Module  $module  The module to which the sensor belongs
     * @return \Illuminate\Http\RedirectResponse Redirects back with success message
     *
     * @throws \Illuminate\Validation\ValidationException When validation fails
     */
    public function store(Request $request, Module $module): RedirectResponse
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
