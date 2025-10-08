<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Repositories\Apis\Users\HealthcareProfessionalRepository;
use Illuminate\Http\Request;

class HealthcareProfessionalController extends Controller
{
    
    protected $healthcareRepository;

    function __construct(HealthcareProfessionalRepository $healthcareRepository)
    {
        $this->healthcareRepository = $healthcareRepository;
    }

    public function healthcareProfessional()
    {
        return $this->healthcareRepository->availableHealthcareProfessionals();
    }
}
