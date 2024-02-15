<?php

namespace App\Http\Controllers;

use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function create(Request $request){
        
        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
       
        return response()->json([
            'success'=>  true,
            'message'=> 'Usario creado exitosamente',
        ]);

    }
      public function login(Request $request){
        
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'success'=> false, 
                'error' => 'No autorizado'], 401);
        };
        $user = User::where('email', $request->email)->first();
        
        $email = $user->email;
        $date_login = now();
        $random_number = mt_rand(200,500);

        $ticket_token = "$email|$date_login|$random_number";
            
       
        return response()->json([
            'success'=> true,
            'token' => $user->createToken($ticket_token,['*'],now()->addHour(1))->plainTextToken
        ]);
        
    }
}
