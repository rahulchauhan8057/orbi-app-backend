<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocationHistory extends Model
{
   use HasFactory;

    protected $fillable = ['user_id', 'location_type', 'address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
