<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppointmentTest extends TestCase
{
  
    use RefreshDatabase;
    protected $seed = true;

    protected function getToken()
    {
        \DB::table('appointments')->truncate();
        \DB::table('users')->truncate();


        $user = User::factory()->create();
        return JWTAuth::fromUser($user);
    }

    /** @test */
    public function it_can_create_an_appointment()
    {
        $appointmentData = Appointment::factory()->raw();

        $response = $this->withToken($this->getToken())->postJson('/api/user/appointment/store', $appointmentData);
        $response->assertStatus(201)
                ->assertJsonStructure([
                    "status",
                    "message",
                    "data" => [
                        "user_id",
                        "healthcare_professional_id",
                        "appointment_start_time",
                        "appointment_end_time",
                        "status",
                        "created_on",
                        "appointment_number",
                        "department" => [
                            "name",
                            "specialty"
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('appointments', $appointmentData);
    }

    /** @test */
    public function it_requires_mandatory_fields_to_create_appointment()
    {
        $response = $this->withToken($this->getToken())
                ->postJson('/api/user/appointment/store', []);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [],
                    'error' => [
                        'healthcare_professional_id',
                        'appointment_start_time',
                        'appointment_end_time',
                    ],
                ]);
    }

     /** @test */
    public function it_can_mark_appointment_as_completed()
    {
        $appointment = Appointment::factory()->create(['status' => 'booked']);

        $response = $this->withToken($this->getToken())->getJson("/api/user/appointment/{$appointment->id}/complete");
        $response->assertStatus(200)
                ->assertJsonStructure([
                    "status",
                    "message",
                    "data" => [],
                ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'completed',
        ]);
    }

    public function it_can_cancel_appointment()
    {
        $appointment = Appointment::factory()->create(['status' => 'booked']);
        $response = $this->withToken($this->getToken())->getJson("/api/user/appointment/{$appointment->id}/cancel");
        $response->assertStatus(200)
                ->assertJsonStructure([
                    "status",
                    "message",
                    "data" => [],
                ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled',
        ]);
    }

}
