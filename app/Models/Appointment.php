<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'healthcare_professional_id', 'appointment_start_time', 'appointment_end_time', 'status'];

    
    public function healthcareProfessional()
    {
        return $this->belongsTo(HealthcareProfessional::class, 'healthcare_professional_id');
    }
    
}
