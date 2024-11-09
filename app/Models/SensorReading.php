<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorReading extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sensor_id',
        'module_id',
        'value',
        'recorded_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'recorded_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
