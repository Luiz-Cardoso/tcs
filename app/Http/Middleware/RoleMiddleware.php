<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    private $role;

    public function __construct($role = null)
    {
        $this->role = $role;
    }

    public function handle(Request $request, Closure $next, $role)
    {
        $jwt = $request->get('jwt_data');

        if (!$jwt || $jwt['role'] !== $role) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
