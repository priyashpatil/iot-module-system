<?php

namespace App\Models;

use App\Enums\ModuleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'data_items_sent',
    ];

    protected $casts = [
        'status' => ModuleStatus::class,
        'active_since' => 'datetime',
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
}
