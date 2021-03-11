<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\QueryException;

// Import models
use App\Models\Customer;
use App\Models\Prescription;

// Import resources
use App\Http\Resources\CustomerCollection;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'DESC');
		
		if(request()->keyword != '') {
			$customers = $customers->where('code', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('name', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('phone', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('email', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('address', 'LIKE', '%' . request()->keyword . '%');
		}

        if($customers) {
            return new CustomerCollection($customers->get());   
        }

        return response()->json(['status' => 'not found']);
    }

    public function store_prescription(Request $request) {
        try {
            Prescription::store($request->all());
            return response()->json(['status' => 'success']);
        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer_with_prescription = Prescription::with('customer')->where('customers_id', $id)->first();

        if($customer_with_prescription) {
            return response()->json([
                'status' => 'success', 
                'data' => $customer_with_prescription
            ]);
        }

        return response()->json(['status' => 'not found']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer_with_prescription = Prescription::with('customer')->where('customers_id', $id)->first();

        if($customer_with_prescription) {
            return response()->json([
                'status' => 'success', 
                'data' => $customer_with_prescription
            ]);
        }

        return response()->json(['status' => 'not found']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        $customer_requests = $request->except('prescription');

        try {
            $customer->update($customer_requests);

            $prescription_requests = $request->prescription;

            $prescription_requests['customers_id'] = $customer->id;

            $prescription = Prescription::find($prescription_requests['id']);

            $prescription->update($prescription_requests);
            
            return response()->json(['status' => 'success']);
        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        $prescription = Prescription::where('customers_id', $id);

        if($customer && $prescription) {
            if($customer->transactions()->count()) {
                return response()->json(['status' => 'restricted']);
            } 

            try {
                $prescription->delete();
                $customer->delete();
                return response()->json(['status' => 'success']);
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'not found']);
    }
}
