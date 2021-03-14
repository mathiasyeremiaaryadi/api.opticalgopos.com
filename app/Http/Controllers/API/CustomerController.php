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
                                ->orWhere('phone', 'LIKE', '%' . request()->keyword . '%');
		}

        if($customers) {
            return new CustomerCollection($customers->get());   
        }

        return response()->json(['status' => 'not found']);
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
        $customer = Customer::find($id);

        if($customer) {
            return response()->json([
                'status' => 'success', 
                'data' => $customer
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

        try {
            $customer->update($request->only(['name', 'phone', 'email', 'address']));            
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

    public function show_prescription($id) {
        $customer = Customer::find($id);
        $prescriptions = Prescription::where('customers_id', $id)->get();
        
        if($customer && $prescriptions) {
            return response()->json([
                'status' => 'success', 
                'data' => [
                    'customer' => $customer,
                    'prescriptions' => $prescriptions
                ]
            ]);
        }

        return response()->json(['status' => 'not found']);
    }

    public function store_prescription(Request $request, $id) {
        try {
            Prescription::create($request->only(['right_spherical', 'right_cylinder', 'right_plus', 'right_axis', 'right_pupil_distance', 'left_spherical', 'left_cylinder','left_plus', 'left_axis', 'left_pupil_distance', 'customers_id']));
            return response()->json(['status' => 'success']);
        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }
    }

    public function destroy_prescription($id) {
        $prescription = Prescription::find($id);

        if($prescription) {
            try {
                $prescription->delete();
                return response()->json(['status' => 'success']);
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'not found']);
    }
}
