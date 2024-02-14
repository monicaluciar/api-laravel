<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ApiController extends Controller
{

    public $json_response = [
        'success' => false,
        'data' => []
    ];
    public function getCustomers( Request $request)
    {
        $this->validate($request, [
            'dni' => 'numeric',
            'email' => 'email',
        ]);
        //agregar exception si falla la validacion
        $condition = array();
        if($request->has('email')){
            $condition = ['email', $request->email] ;
        }
        elseif($request->has('dni')){
            $condition = ['dni', $request->dni];
        }
        $query = Customers::select(
            'customers.name',
            'customers.last_name',
            'customers.address',
            'communes.description as commune',
            'regions.description as region',
            )
            ->join('regions', 'regions.id_reg', '=', 'customers.id_reg')
            ->join('communes', 'communes.id_com', '=', 'customers.id_com')
            ->where('customers.status', 'A');

        if($condition){
            $query->where($condition[0], $condition[1]);
        }
        $customers = $query->get();
       
        $this->json_response['success'] = true;
        $this->json_response['data'] = $customers;
        return response()->json($this->json_response);
    }
    public function createCustomer(Request $request){

        $this->validate($request, [
            'dni' => 'required|numeric',
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'address' => 'string',
            'region' => 'required|string',
            'commune' => 'required|string'
        ]);
        //agregar exception si falla la validacion

        if(Customers::where('email', $request->email)->first() != null){
            return response()->json(array_merge($this->json_response, ['error'=> 'El email ya existe']));
        }else{
            $region = \DB::table('regions')->where('description', $request->region)->first();
            if($region){
                $commune_by_region = \DB::table('communes')
                    ->join('regions','communes.id_reg','=','regions.id_reg')
                    ->where('communes.description',$request->commune)
                    ->where('communes.id_reg', $region->id_reg)
                    ->select('communes.id_com','communes.id_reg')
                    ->first();

                if($commune_by_region){
                    $customer = Customers::create([   
                        'dni' => $request->dni,
                        'id_reg' => $commune_by_region->id_reg,
                        'id_com' => $commune_by_region->id_com,
                        'name' => $request->name,
                        'email' => $request->email,
                        'last_name'=> $request->last_name,
                        'address'=> $request->address,
                        'date_reg' => now()
                    ]);
                    $this->json_response['success'] = true;
                    return response()->json($this->json_response);
                }
                return response()->json(array_merge($this->json_response, ['error'=> 'La comuna no existe o no pertenece a esa region']));                
            }else{
                return response()->json(array_merge($this->json_response, ['error'=> 'La region no existe']));
            }
            
        }


        
    }
    public function deleteCustomer(Request $request){
        $customer = Customers::where('email', $request->email)->where('status','!=','trash')->first();
        //validar que esta activo o inactivo y si no, decir que no existe
        if($customer){
            $customer->status = 'trash';
            $customer->save();
            $this->json_response['success'] = true;
            return response()->json($this->json_response);
        }

        return response()->json(array_merge($this->json_response, ['error'=> 'Registro no existe']));
    }

    // public function login(Request $request){
    //     $response = [
    //         "status" => false,
    //         "message" => "Invalid credentials"
    //     ];

    //     $data = json_decode($request->getContent(), true);

    //     $customer = Customers::where('email', $request->email)->first();
    //     if($customer && Hash::check($request->password, $customer->password)){
    //         return response()->json($customer);
    //     }else{
    //         return response()->json(['error' => 'Invalid credentials']);
    //     }
    // }
}
