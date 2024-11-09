<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page with a paginated list of modules.
     *
     * Fetches all modules ordered by latest first and returns them to the dashboard view.
     * The dashboard view displays module information and includes functionality to:
     * - Show module name and description
     * - Provide interface for adding new sensors
     * - Display modules in a paginated format
     *
     * @param  Request  $request  The incoming HTTP request
     * @return View Returns the dashboard view with paginated modules
     */
    public function __invoke(Request $request): View
    {
        $modules = Module::latest()->paginate();

        return view('dashboard', compact('modules'));
    }
}
