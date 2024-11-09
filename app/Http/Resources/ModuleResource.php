<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request  The incoming HTTP request
     * @return array<string, mixed> Array containing the transformed module data
     *
     * The returned array includes:
     * - Basic module information (id, name, description, status)
     * - Status styling class for UI representation
     * - Operating time statistics
     * - Metric count for data items sent
     * - Associated failures with timestamps
     * - Connected sensors with their readings
     */
    public function toArray(Request $request): array
    {
        return [
            // Basic module information
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'status_class' => $this->statusBadgeClass(),
            'operating_time' => $this->operatingTime(),
            'metric_count' => $this->data_items_sent,

            // Failures collection transformation
            'failures' => $this->failures->map(function ($failure) {
                return [
                    'id' => $failure->id,
                    'description' => $failure->description,
                    'diff_for_humans' => $failure->failure_at->diffForHumans(), // Human-readable time difference
                    'error_code' => $failure->error_code,
                    'failure_at' => $failure->failure_at->format('Y-m-d H:i:s'), // Formatted timestamp
                ];
            }),

            // Sensors collection transformation with their readings
            'sensors' => $this->sensors->map(function ($sensor) {
                return [
                    'id' => $sensor->id,
                    'name' => $sensor->name,
                    'unit' => $sensor->unit,
                    'current_value' => $sensor->current_value ?? 'N/A', // Current sensor value or N/A if null
                    'readings' => $sensor->readings->map(function ($reading) {
                        return [
                            'value' => $reading->value,
                            'timestamp' => $reading->recorded_at->format('Y-m-d H:i:s'), // Formatted timestamp
                        ];
                    }),
                ];
            }),
        ];
    }
}
