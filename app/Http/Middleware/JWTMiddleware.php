<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\JWTService;

class JWTMiddleware
{
    public function handle(Request $request, Closure $next)
    {    
        $authHeader = $request->header('Authorization');

        if(!$authHeader || !str_starts_with($authHeader, 'Bearer ')){
            return response()->json(['message' => 'Token missing'], 401);
        }

        $token = substr($authHeader, 7);
        $decoded = JWTService::validateToken($token);

        if(!$decoded){
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $request->merge(['jwt_data' => $decoded]);

        return $next($request);
    }
}
