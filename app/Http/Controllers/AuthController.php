<?php

namespace App\Http\Controllers;

use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public $json_response = [
        'success' => false,
        'data' => []
    ];
    public function create(Request $request){
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(array_merge($this->json_response,['error' => $validator->errors()->all()]), 400);
        };
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
       
        return response()->json([
            'success'=>  true,
            'message'=> 'User created successfully',
        ]);

    }
      public function login(Request $request){
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(array_merge($this->json_response,['error' => $validator->errors()->all()]), 400);
        };
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json(array_merge($this->json_response, ['error' => 'No autorizado']), 401);
        };
        $user = User::where('email', $request->email)->first();
        // $token = sha1($user->email . time() . rand(200, 500));

        // // Almacena el token en la base de datos
        // Token::create([
        //     'user_id' => $user->id,
        //     'token' => $token,
        //     'expiration_date' => now()->addHours(24), // Por ejemplo, 24 horas de validez
        // ]);
        
        $email = $user->email;
        $date_login = now();
        $random_number = mt_rand(200,500);

        $ticket_token = "$email|$date_login|$random_number";
        $encrypted_token = sha1($ticket_token);
        $this->json_response['success'] = true;
        $this->json_response['token'] = $user->createToken($encrypted_token)->plainTextToken;
        return response()->json($this->json_response);
        
    }
}
