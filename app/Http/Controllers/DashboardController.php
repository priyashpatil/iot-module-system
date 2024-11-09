<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $modules = Module::latest()->paginate();

        return view('dashboard', compact('modules'));
    }
}
