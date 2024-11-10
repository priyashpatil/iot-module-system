<?php

namespace App\Models;

use App\Enums\ModuleStatus;
use App\Observers\ModuleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([ModuleObserver::class])]
class Module extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'started_at',
        'stopped_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ModuleStatus::class,
        'started_at' => 'datetime:Y-m-d H:i:s',
        'stopped_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the sensors associated with this module.
     */
    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class);
    }

    /**
     * Get the sensor readings associated with this module.
     */
    public function sensorReadings(): HasMany
    {
        return $this->hasMany(SensorReading::class);
    }

    /**
     * Get the failures recorded for this module.
     */
    public function failures(): HasMany
    {
        return $this->hasMany(ModuleFailure::class);
    }

    /**
     * Get the Bootstrap badge class based on the module's current status.
     *
     * @return string The CSS class for the status badge
     */
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            ModuleStatus::OPERATIONAL => 'text-bg-success',
            ModuleStatus::MALFUNCTION => 'text-bg-danger',
            ModuleStatus::DEACTIVATED => 'text-bg-warning',
            default => 'text-bg-light'
        };
    }

    /**
     * Calculate the operating time of the module.
     *
     * For deactivated modules, returns the duration between start and stop times.
     * For active modules, returns the duration since start time.
     * Returns 'N/A' if no start time is set.
     *
     * @return string Formatted duration string (e.g., "5d 2h 30m 15s") or 'N/A'
     */
    public function operatingTime(): string
    {
        if ($this->status === ModuleStatus::DEACTIVATED && $this->started_at && $this->stopped_at) {
            $duration = $this->started_at->diff($this->stopped_at);

            return $duration->format('%dd %hh %im');
        }

        return $this->started_at
            ? now()->diff($this->started_at)->format('%dd %hh %im')
            : 'N/A';
    }
}
