<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Tymon\JWTAuth\JWTAuth;

class JWTmiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request,Closure $next)
    {
       // Request $request, Closure $next

        try {
            if (!$user = FacadesJWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found',$user], 404);
             }
          //  $user = FacadesJWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error' => 'invalid token'], 400);
            }
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error' => 'Token is expired'], 500);
            }
            return response()->json(['error' => 'Token not found'], 500);
        }
        return $next($request);
    }
}
