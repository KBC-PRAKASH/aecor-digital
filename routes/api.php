<?php

use App\Http\Controllers\Apis\AppointmentController;
use App\Http\Controllers\Apis\AuthController;
use App\Http\Controllers\Apis\HealthcareProfessionalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyJwtToken;

Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

/**
 * Authenticated routes for users
*/
Route::group(['middleware' => [VerifyJwtToken::class]], function() {
    Route::get('/user/logout', [AuthController::class, 'logout']);


    Route::get('/available-healthcare-professionals', [HealthcareProfessionalController::class, 'healthcareProfessional']);

    // Appointment routes
    Route::get('/user/appointments', [AppointmentController::class, 'userAppointments']);

    Route::post('/user/appointment/store', [AppointmentController::class, 'store']);
    Route::get('/user/appointment/{id}/cancel', [AppointmentController::class, 'cancelAppointment']);
    Route::get('/user/appointment/{id}/complete', [AppointmentController::class, 'markCompleteAppointment']);


});
