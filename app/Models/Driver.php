<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone_no',
        'alternative_no',
        'profile',
        'dob',
        'otp',
        'otp_expires_at',
        'vehicle_brand',
        'vehicle_plate_no',
        'vehicle_photo',
        'vehicle_rc_front_photo',
        'vehicle_rc_back_photo',
        'vehicle_capicty',
        'vehicle_production_year',
        'commercial_permit_year',
        'status',
    ];

}
