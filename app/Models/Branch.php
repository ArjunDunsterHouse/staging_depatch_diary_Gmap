<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    
    protected $connection='mysql';
    
    protected $table='branch';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'branch_location',
        'branch_code',
        'shipping_agent_code',
        'branch_postcode',
        'latitude',
        'longitude',
    ];
}
