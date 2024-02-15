<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthetication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        // Verifica si el token existe y no ha expirado
        if (!$this->isValidToken($token)) {
            return response()->json(['error' => 'Token inválido o expirado'], 401);
        }

        return $next($request);
    }

    public function isValidToken($token){
        
    }
}
