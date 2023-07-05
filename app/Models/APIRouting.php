<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APIRouting extends Model
{
    use HasFactory;
    protected $connection='mysql';
    
    protected $table='api_routing';

    protected $fillable = [
        'unique_id', 
        'branch_id',
        'vehicle_id',
        'order_nos',
        'promised_deilivery_date',
        'distance_miles',
        'time_seconds',
        'original_route',
        'optimized route',
        'display_orders_position',
        'error_status',
        'last_update'

    ];
}
