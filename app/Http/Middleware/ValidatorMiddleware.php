<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class ValidatorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $validator = null;
        if ($request->is('api/customers')) {
            $validator = Validator::make($request->all(), [
                'dni' => 'numeric',
                'email' => 'email',
            ]);
        } elseif ($request->is('api/customer') && $request->method() == 'POST') {   
            $validator = Validator::make($request->all(), [
                'dni' => 'required|numeric',
                'name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'address' => 'string',
                'region' => 'required|string',
                'commune' => 'required|string'
            ]);     
        }elseif ($request->is('api/customer') && $request->method() == 'DELETE') {
            $validator = Validator::make($request->all(), [
                'dni' => 'numeric',
                'email' => 'email',
            ]);
        
        }elseif ($request->is('api/auth/register')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6|max:10',
            ]);
        }elseif ($request->is('api/auth/login')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6|max:10',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error' => $validator->errors()], 422);
        }
        
        return $next($request);
    }
}
