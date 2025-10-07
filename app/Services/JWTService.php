<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;

class JWTService
{
    public static function generateToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'exp' => Carbon::now()->addSeconds(env('JWT_TTL', 3600))->timestamp,
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        return $jwt;
    }

    public static function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}
