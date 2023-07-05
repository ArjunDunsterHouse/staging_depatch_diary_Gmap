<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBookingStatus extends Model
{
    use HasFactory;
    protected $connection='mysql';
    
    protected $table='vehicle_booking';
    protected $fillable = [
        'vehicle_id', 
        'branch_id',
        'vehicle_booking_date',
        'vehicle_booking_status'
    ];
}
