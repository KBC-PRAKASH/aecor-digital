<?php
declare(strict_types=1);
namespace App\Interfaces;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AppointmentInterface
{

    public function appointmentsByUser(): JsonResponse;

    public function bookAppointment(Request $request): JsonResponse;

    public static function isDuplicate($healthcareProfessionalId, $startTime, $endTime): bool;

    public function cancelAppointment(Appointment $id): JsonResponse;

    public function markCompleteAppointment(Appointment $id): JsonResponse;


}