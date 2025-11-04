<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterQualityData extends Model
{
    use HasFactory;

    protected $table = 'water_quality_data';

    protected $fillable = [
        'conductivity',
        'ph', 
        'oxygen',
        'temperature',
        'measured_at'
    ];

    protected $casts = [
        'conductivity' => 'decimal:2',
        'ph' => 'decimal:2',
        'oxygen' => 'decimal:2',
        'temperature' => 'decimal:2',
        'measured_at' => 'datetime'
    ];
}