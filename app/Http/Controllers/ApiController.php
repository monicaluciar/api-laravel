<?php

namespace App\Http\Controllers;

use App\Models\Communes;
use App\Models\Customers;
use App\Models\Regions;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        Log::info('getCustomers');
        $this->validate($request, [
            'dni' => 'numeric',
            'email' => 'email',
        ]);
        //agregar exception si falla la validacion
        
        $customer_query = Customers::select(
            'customers.name',
            'customers.last_name',
            'customers.address',
            'communes.description as commune',
            'regions.description as region',
            )
            ->join('regions', 'regions.id_reg', '=', 'customers.id_reg')
            ->join('communes', 'communes.id_com', '=', 'customers.id_com')
            ->where(function ($query) use ($request) {
                $query->where('customers.status', 'A');
                if($request->has('email')){
                    $query->where('customers.email', $request->email);
                }
                if($request->has('dni')){
                    $query->where('customers.dni', $request->dni);
                }
            });
        
        $customers = $customer_query->get();
       
        $this->json_response['success'] = true;
        $this->json_response['data'] = $customers;
        return response()->json($this->json_response);
    }
    public function createCustomer(Request $request){

        if(Customers::where('email', $request->email)->first() != null){
            return response()->json(array_merge($this->json_response, ['error'=> 'El email ya existe']));
        }else{
            $region = Regions::where('description', $request->region)->first();
            if($region){
                $commune_by_region = DB::table('communes')
                    ->join('regions','communes.id_reg','=','regions.id_reg')
                    ->where('communes.description',$request->commune)
                    ->where('communes.id_reg', $region->id_reg)
                    ->select('communes.id_com','communes.id_reg')
                    ->first();

                if($commune_by_region){
                    Customers::create([   
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
        $customer = Customers::where(function ($query) use ($request) {
            $query->where('email', $request->email)
                ->orWhere('dni', $request->dni);
        })
        ->where('status', '!=', 'trash')
        ->first();

        if($customer){
            $customer->status = 'trash';
            $customer->save();
            $this->json_response['success'] = true;
            return response()->json($customer);
        }

        return response()->json(array_merge($this->json_response, ['error'=> 'Registro no existe']),404);
    }

  
}
