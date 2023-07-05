<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryRecords extends Model
{
    use HasFactory;
    protected $connection='mysql';
    protected $table='diary';
    protected $fillable = [
        'order_no', 
        'ship_to_name',
        'ship_to_post_code',
        'ship_to_country',
        'ship_to_region_code',
        'type_of_supply_code',
        'order_weight',
        'order_amount',
        'location_code',
        'shipping_agent_code',
        'shipping_agent_service_code',
        'shipment_type',
        'promised_delivery_date',
        'delivery_confirmed',
        'balance_amount',
        'last_shipping_no',
        'ship_status',
        'completed',
        'ship_to_city',
        'dispatch_change',
        'dispatch_requested_date'
    ];
    
}
