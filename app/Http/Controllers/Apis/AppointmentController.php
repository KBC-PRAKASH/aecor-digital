<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Repositories\Apis\Users\AppointmentRepository;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    
    protected $appointmentRepository;
    function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }


    public function userAppointments()
    {
        return $this->appointmentRepository->appointmentsByUser();
    }
    
    public function store(Request $request)
    {
        return $this->appointmentRepository->bookAppointment($request);
    }

    public function cancelAppointment($id)
    {
        return $this->appointmentRepository->cancelAppointment($id);
    }

    public function markCompleteAppointment($id)
    {
       return $this->appointmentRepository->markCompleteAppointment($id); 
    }
}
