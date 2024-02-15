<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('api.key')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'create']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/customers', [ApiController::class, 'getCustomers']);
    Route::post('/customer', [ApiController::class, 'createCustomer']);
    Route::delete('/customer', [ApiController::class, 'deleteCustomer']);
    
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/customers', [ApiController::class, 'getCustomers']);
    Route::post('/customer', [ApiController::class, 'createCustomer']);
    Route::delete('/customer', [ApiController::class, 'deleteCustomer']);
});
// Route::middleware('sql.injection')->group(function () {
//     Route::post('/auth/register', [AuthController::class, 'create']);
//     Route::post('/auth/login', [AuthController::class, 'login']);
//     Route::post('/customer', [ApiController::class, 'createCustomer']);

// });



