<?php
declare(strict_types=1); 
namespace App\Repositories\Apis\Users;

use App\Models\HealthcareProfessional;
use Exception;
use Illuminate\Http\Request; 


class HealthcareProfessionalRepository {

    public function availableHealthcareProfessionals()
    {
        try {
            $healthcareProfessionals = HealthcareProfessional::get(['id', 'name', 'specialty']);
            return response()->apiSuccess($healthcareProfessionals, "Record found successfully", 200);

        } catch(Exception $e) {
            return response()->apiCatchError();
        }
        
    }

}
