<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

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
        ]);

        Module::create($validated);

        return redirect()->back()
            ->with(['alert' => [
                'message' => 'Module Created Successfully',
                'type' => 'success',
            ]], 201);
    }
}
