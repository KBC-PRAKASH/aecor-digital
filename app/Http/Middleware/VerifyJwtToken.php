<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class VerifyJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header();
        if(empty($token['authorization'])){
            return response()->apiError('Unauthorized', 401, 'Authorization Token not found');
        }else{
            try {
                $user = auth('api')->user();
                if(empty($user)){
                    return response()->apiError('Unauthorized', 401, 'Your are not loggedIn');
                }else{
                    return $next($request);
                }
                
            } catch (\Exception $e) {
                return response()->apiError('Unauthorized', 401, 'The token is invalid');
            }
        }
       
    }
}
