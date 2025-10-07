<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Repositories\Apis\Users\AuthRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    
    protected $authRepository;

    function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(Request $request) 
    {
        return $this->authRepository->registerHandler($request);
    }

    public function login(Request $request)
    {
        return $this->authRepository->loginHandler($request);
    }

    public function logout(Request $request)
    {
        return $this->authRepository->logout();
    }
}
