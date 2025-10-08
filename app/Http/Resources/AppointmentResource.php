<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $healthcareDetails = $this->healthcareProfessional;
        return [
            'appointment_id'    => $this->id,
            'appointment_start_time' => $this->appointment_start_time,
            'appointment_end_time'  => $this->appointment_end_time,
            'status'    => $this->status,
            'department' => [
                'name'  => $healthcareDetails->name,
                'specialty' => $healthcareDetails->specialty
            ]

        ];
    }
}
