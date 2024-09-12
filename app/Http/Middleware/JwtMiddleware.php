<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware {
    public function handle($request, Closure $next) {
        try {
            $token = $request->cookie('token');
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            } else {
                $user = JWTAuth::setToken($token)->authenticate();

                if (!$user) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                $request->merge(['user' => $user]);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token error'], 401);
        }
        return $next($request);
    }
}
