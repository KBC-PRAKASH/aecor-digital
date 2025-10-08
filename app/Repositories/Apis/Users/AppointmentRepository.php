<?php
declare(strict_types=1); 
namespace App\Repositories\Apis\Users;

use App\Http\Resources\AppointmentResource;
use App\Interfaces\AppointmentInterface;
use App\Models\Appointment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppointmentRepository implements AppointmentInterface {

    protected $userId;

    function __construct()
    {
        $this->userId = auth('api')->user()->id;
    }

    public function appointmentsByUser(): JsonResponse
    {
        try {
            $appointments = Appointment::where('user_id', $this->userId)->get();
            if (count($appointments) > 1) {
                return response()->apiSuccess(AppointmentResource::collection($appointments), "Appointments found successfully", 200);
            }
            return response()->apiSuccess([], "No appointment found", 200);
        } catch(Exception $e) {
            return response()->apiCatchError($e);
        }
    }


    public function bookAppointment(Request $request): JsonResponse
    {
        try {
            // validation
            $validator = Validator::make($request->all(), [
                'healthcare_professional_id' => 'required|integer|exists:healthcare_professionals,id',
                'appointment_start_time'     => [
                    'required',
                    'date',
                    Rule::date()->afterOrEqual(today()),
                    Rule::date()->before(today()->addDays(15)),
                ],
                'appointment_end_time'       => [
                    'required',
                    'date',
                    Rule::date()->afterOrEqual(today()),
                    Rule::date()->before(today()->addDays(15)),
                ]
            ]);

            if ($validator->fails()) {
                return response()->apiError("You have some validation errors", 422, $validator->errors());
            }

            // Check for duplicate
            $isDuplicate = self::isDuplicate($request->healthcare_professional_id, $request->appointment_start_time, $request->appointment_end_time);
            
            if ($isDuplicate) {
                return response()->apiError("Conflict", 409, 'You cannot book an appointment as the provider is not available at this time. Please select another date and time.');
            }

            $appointment = [
                'user_id'   => $this->userId,
                'healthcare_professional_id' => $request->healthcare_professional_id,
                'appointment_start_time'    => $request->appointment_start_time,
                'appointment_end_time'      => $request->appointment_end_time,
                'status'                    => 'booked'
            ];

            $appointmentRecord = Appointment::create($appointment);

            $appointment['created_on'] = $appointmentRecord->created_at;
            $appointment['appointment_number'] = $appointmentRecord->id;
            $healthcareDetails = $appointmentRecord->healthcareProfessional;
            $appointment['department']  = [
                'name'  => $healthcareDetails->name,
                'specialty' => $healthcareDetails->specialty
            ];

            return response()->apiSuccess($appointment, "Your appointment have been booked successfully", 201);
        } catch(Exception $e) {
            return response()->apiCatchError($e);
        }
    }

    public function cancelAppointment($id): JsonResponse
    {
        try {
            $now = Carbon::now();
            $appointment = Appointment::where(['status' => 'booked', 'id' => $id])->first();
            if (empty($appointment)) {
                return response()->apiError("Forbidden", 403, 'Your request can not be processed, as your appointment is either closed or completed');
            }
            $appointmentStart = Carbon::parse($appointment->appointment_start_time);
            if ($appointmentStart->gt(Carbon::now()->addDay())) {
                $appointment->update(['status' => 'cancelled']);
                return response()->apiSuccess([], "Your appointment have been cancelled successfully", 200);
            } else {
                return response()->apiError("Forbidden", 403, 'Cancellation not allowed within 24 hours of the appointment time');
            }
        } catch(Exception $e) {
            return response()->apiCatchError($e);
        }
    }

    public function markCompleteAppointment($id): JsonResponse
    {
        try {
            $appointment = Appointment::where(['status' => 'booked', 'id' => $id])->first();
            if (empty($appointment)) {
                return response()->apiError("Forbidden", 403, 'Your request can not be processed, as your appointment is already closed or completed');
            }
            $appointment->update(['status' => 'completed']);
            return response()->apiSuccess([], "Your appointment has been marked as completed", 200);

        } catch(Exception $e) {
            return response()->apiCatchError($e);
        }
    }

    public static function isDuplicate($healthcareProfessionalId, $startTime, $endTime): bool
    {
        try {
            return Appointment::where('status', 'booked')
                    ->where('healthcare_professional_id', $healthcareProfessionalId)
                    ->where(function ($query) use ($startTime, $endTime) {
                        $query->whereBetween('appointment_start_time', [$startTime, $endTime])
                            ->orWhereBetween('appointment_end_time', [$startTime, $endTime])
                            ->orWhere(function ($q) use ($startTime, $endTime) {
                                $q->where('appointment_start_time', '<', $startTime)
                                    ->where('appointment_end_time', '>', $endTime);
                            });
                    })
                    ->count() >= 1 ? true : false;
        } catch(Exception $e) {
            return false;
        }
        
    }



}
