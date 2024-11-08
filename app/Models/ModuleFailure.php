<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleFailure extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'module_id',
        'failure_at',
        'description',
        'error_code',
    ];

    protected $casts = [
        'failure_at' => 'datetime',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
