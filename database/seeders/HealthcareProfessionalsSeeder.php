<?php

namespace Database\Seeders;

use App\Models\HealthcareProfessional;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthcareProfessionalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $healthcareProfessionals = [
            [
                'name'      => 'Cardiologist',
                'specialty' => 'Heart and blood vessel diseases'
            ],
            [
                'name'      => 'Dermatologist',
                'specialty' => 'Skin, hair, and nails'
            ],
            [
                'name'      => 'Neurologist',
                'specialty' => 'Nervous system disorders'
            ],
            [
                'name'      => 'Oncologist',
                'specialty' => 'Cancer diagnosis and treatment'
            ],
            [
                'name'      => 'Nephrologist',
                'specialty' => 'Kidney diseases'
            ],
            [
                'name'      => 'Hematologist',
                'specialty' => 'Blood disorders and blood-forming organs'
            ],
            [
                'name'      => 'Ophthalmologist',
                'specialty' => 'Comprehensive eye care and eye surgery'
            ],
            [
                'name'      => 'Otolaryngologist (ENT)',
                'specialty' => 'Ear, nose, throat, head, and neck conditions'
            ],
            [
                'name'      => 'Radiologist',
                'specialty' => 'Medical imaging-based diagnosis'
            ],
            [
                'name'      => 'Pathologist',
                'specialty' => 'Disease diagnosis through tissue and fluid analysis'
            ],
            [
                'name'      => 'Physical Therapist',
                'specialty' => 'BloRehabilitation and physical medicine'
            ],
        ];

        if (HealthcareProfessional::count() === 0 ) {
            HealthcareProfessional::insert($healthcareProfessionals);
        }
    }
}
