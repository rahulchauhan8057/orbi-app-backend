<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverVerification extends Model
{
    protected $fillable = [
    'driver_id',
    'licence_no',
    'licence_front_photo',
    'licence_back_photo',
    'licence_expire_date',
    'selfie_with_driver_licence',
    'aadhar_no',
    'aadhar_front_photo',
    'aadhar_back_photo',
    'police_verification_certificate',
    'verification_status',
    'rejection_reason',
];

}
