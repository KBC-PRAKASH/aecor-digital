<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'healthcare_professional_id', 'appointment_start_time', 'appointment_end_time', 'status'];


    
}
