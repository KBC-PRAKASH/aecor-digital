<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $healthcareProfessional = HealthcareProfessional::factory()->create();
        $user = User::factory()->create();
        $faker = \Faker\Factory::create();
        $startTime = $faker->dateTimeBetween('now', '+30 minutes');
        return [
            'user_id' => $user->id,
            'healthcare_professional_id' => $healthcareProfessional->id,
            'appointment_start_time' => $startTime->format('Y-m-d H:i'),
            'appointment_end_time' => $faker->dateTimeBetween($startTime->format('Y-m-d H:i:s') . ' +1 minute', $startTime->format('Y-m-d H:i:s') . ' +1 hour')->format('Y-m-d H:i'),
            'status' => 'booked',
        ];
    }
}
