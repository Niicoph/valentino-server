<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Exception;

class AuthController extends Controller {
    
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $response = response()->json(['message' => 'Invalid credentials'], 401);
            } else {
                $response = response()->json(['message' => 'Logged in successfully'])->cookie('token', $token, 60, '/', null, true, true); 
            }
        } catch (Exception $e) {
            $response = response()->json(['error' => $e], 500);
        }
        return $response;
    }
    public function logout() {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            $response = response()->json(['message' => 'Logged out successfully'])
                ->cookie('token', '', -1, '/', null, true, true);
        } catch (JWTException $e) {
            $response = response()->json(['error' => 'Could not logout'], 500);
        }
        return $response;
    }
    public function isAuth(Request $request) {
        $response = null;
    
        try {
            $token = $request->cookie('token');
    
            if (!$token) {
                $response = response()->json(['error' => 'Token not provided'], 401);
            } else {
                $user = JWTAuth::setToken($token)->authenticate();
    
                if (!$user) {
                    $response = response()->json(['error' => 'Unauthorized'], 401);
                } else {
                    $request->merge(['user' => $user]);
                    $response = response()->json(['status' => 'Authenticated'], 200);
                }
            }
        } catch (JWTException $e) {
            $response = response()->json(['error' => 'Token error'], 401);
        }
    
        return $response;
    }
    


}
