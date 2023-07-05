<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryComments extends Model
{
    use HasFactory;
    protected $connection='mysql';
    
    protected $table='vehicle_diary_comments';

    protected $fillable = [
        'vehicle_id', 
        'vehicle_diary_date',
        'comments'
    ];
}
