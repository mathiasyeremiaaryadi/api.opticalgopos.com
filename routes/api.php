<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth resource access
Route::post('login', 'API\AuthController@login')->name('login');

Route::middleware('auth:sanctum')->group(function () {
    // Products REST-API resource access
    Route::resource('products', API\ProductController::class);

    // Brands REST-API resource access
    Route::resource('brands', API\BrandController::class);

    // Categories REST-API resource access
    Route::resource('categories', API\CategoryController::class);

    // Employees REST-API resource access
    Route::resource('employees', API\EmployeeController::class);
	Route::put('employees/{id}/profile', 'API\EmployeeController@update_profile');

    // Payments REST-API resource access
    Route::resource('payments', API\PaymentController::class);

    // Stocks REST-API resource access
    Route::resource('stocks', API\StockController::class);

    // Owners REST-API resource access
    Route::resource('transactions', API\TransactionController::class);

    // Customers REST-API resource access
    Route::resource('customers', API\CustomerController::class);
    Route::post('customers/prescription', 'API\CustomerController@store_prescription');

    // Admins REST-API resource access
    Route::resource('admins', API\OwnerController::class);
});


