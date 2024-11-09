<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Illuminate\Support\Facades\Cache;

class ModuleController extends Controller
{
    /**
     * Cache TTL in seconds (1 minute)
     */
    private const CACHE_TTL = 60;

    /**
     * Loads and returns a module with its related data from cache or database.
     *
     * Includes:
     * - Latest 10 failures ordered by failure_at descending
     * - Latest 60 sensor readings for each sensor ordered by recorded_at descending
     *
     * @param  Module  $module  The module to load
     * @return ModuleResource A JSON resource containing the module and its related data
     */
    public function __invoke(Module $module): ModuleResource
    {
        $cacheKey = "module_{$module->id}_data";

        $moduleData = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($module) {
            return $module->load([
                'failures' => function ($query) {
                    $query->latest('failure_at')->limit(10);
                },
                'sensors.readings' => function ($query) {
                    $query->latest('recorded_at')->limit(60);
                },
            ]);
        });

        return new ModuleResource($moduleData);
    }
}
