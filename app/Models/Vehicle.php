<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $connection='mysql';
    protected $table='vehicle';


    protected $fillable = [
        'van_number', 
        'start_date',
        'end_date',
        'branch_id',
        'shipping_agent_service_code',
        'delivery_capacity',
        'target_amount',
        'registration_number',
        'vehicle_type',
        'vehicle_status',
        'vehicle_bookable'
    ];
}
