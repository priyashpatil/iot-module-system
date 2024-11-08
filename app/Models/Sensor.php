<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    /** @use HasFactory<\Database\Factories\SensorFactory> */
    use HasFactory;

    protected $fillable = [
        'module_id',
        'name',
        'unit',
        'current_value',
    ];

    protected $casts = [
        'current_value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(SensorReading::class);
    }
}
