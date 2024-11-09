<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'operating_time' => $this->active_since ? $this->active_since->diff(now())->format('%dd %hh %im') : 'N/A',
            'metric_count' => $this->data_items_sent,
            'failures' => $this->failures->map(function ($failure) {
                return [
                    'id' => $failure->id,
                    'description' => $failure->description,
                    'diff_for_humans' => $failure->failure_at->diffForHumans(),
                    'error_code' => $failure->error_code,
                    'failure_at' => $failure->failure_at->format('Y-m-d H:i:s'),
                ];
            }),
            'sensors' => $this->sensors->map(function ($sensor) {
                return [
                    'id' => $sensor->id,
                    'name' => $sensor->name,
                    'unit' => $sensor->unit,
                    'readings' => $sensor->readings->map(function ($reading) {
                        return [
                            'value' => $reading->value,
                            'timestamp' => $reading->recorded_at->format('Y-m-d H:i:s'),
                        ];
                    }),
                ];
            }),
        ];
    }
}
