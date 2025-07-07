<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ride extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'driver_id',
        'request_id',
        'pick_up_location',
        'drop_location',
        'pickup_lat',
        'pickup_lng',
        'drop_lat',
        'drop_lng',
        'vehicle_type',
        'final_amount',
        'distance_km',
        'estimated_time',
        'ride_type',
        'number_of_passenger',
        'weight',
        'height',
        'length',
        'breadth',
        'cancel_type',
        'status',
    ];
}
