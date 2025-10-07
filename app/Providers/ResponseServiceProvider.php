<?php

namespace App\Providers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('apiSuccess', function($data = [], $message = null, int $code = 200): JsonResponse {
            return response()->json([
                'status'  => true,
                'message' => $message,
                'data'    => $data
            ], $code);
        });

        Response::macro('apiError', function($message = null, int $code, $error = null, $data = []): JsonResponse {
            return response()->json([
                'status'  => false,
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $code);
        });

        Response::macro('apiCatchError', function(Exception $e): JsonResponse {
            $errorMessage = $e->getMessage();
            $errorLine = $e->getLine();
            $errorFile = $e->getFile(); 
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong, please try again after some time',
                'error'   => "Error: $errorMessage in $errorFile on line $errorLine"
            ], 400);
        });
    }
}
