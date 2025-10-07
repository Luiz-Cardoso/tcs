<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\JWTService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->firt();

        if(!$user || Hash::check($credentials['password'], $user->password)){
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = JWTService::generateToken($user);

        return response()->json([
            'token' => $token,
            'expires_in' => env('JWT_TTL', 3600)
        ], 200);
    }

    public function logout(Request $request)
    {
        $jwt = $request->header('Authorization');

        if(!$jwt || !str_starts_with($jwt, 'Bearer ')){
            return response()->json(['message' => 'Invalid Token'], 401);
        }

        $token = substr($jwt, 7);
        $decoded = \App\Services\JWTService::validateToken($token);

        if(!decoded){
            return response()->json(['message' => 'Invalid Token'], 401);
        }

        return response()->json(['message' => 'OK'], 200);

        // Como JWT é stateless, logout é feito no cliente removendo o token.
        // Se quiser, você pode implementar blacklist aqui.
    }
}
