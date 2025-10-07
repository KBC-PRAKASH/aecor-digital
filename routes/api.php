<?php

use App\Http\Controllers\Apis\AuthController;
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

});
