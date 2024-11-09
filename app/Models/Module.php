<?php

namespace App\Models;

use App\Enums\ModuleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'started_at',
        'stopped_at'
    ];

    protected $casts = [
        'status' => ModuleStatus::class,
        'started_at' => 'datetime:Y-m-d H:i:s',
        'stopped_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class);
    }

    public function sensorReadings(): HasMany
    {
        return $this->hasMany(SensorReading::class);
    }

    public function failures(): HasMany
    {
        return $this->hasMany(ModuleFailure::class);
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            ModuleStatus::OPERATIONAL => 'text-bg-success',
            ModuleStatus::MALFUNCTION => 'text-bg-danger',
            ModuleStatus::DEACTIVATED => 'text-bg-warning',
            default => 'text-bg-light'
        };
    }

    public function operatingTime(): string
    {
        if ($this->status === ModuleStatus::DEACTIVATED && $this->started_at && $this->stopped_at) {
            $duration = $this->started_at->diff($this->stopped_at);
            return $duration->format('%dd %hh %im %Ss');
        }

        return $this->started_at
            ? now()->diff($this->started_at)->format('%dd %hh %im %Ss')
            : 'N/A';
    }
}
