<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\QueryException;

// Import models
use App\Models\Payment;

// Import resources
use App\Http\Resources\PaymentCollection;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::orderBy('created_at', 'DESC');
		
		if(request()->keyword != '') {
			$payments = $payments->where('payment_name', 'LIKE', '%' . request()->keyword . '%');
		}

        if($payments) {
            return new PaymentCollection($payments->get());   
        }

        return response()->json(['status' => 'not found']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            Payment::create($request->all());

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
        $payment = Payment::find($id);

        if($payment) {            
            return response()->json([
                'status' => 'success', 
                'data' => $payment
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
        $payment = Payment::find($id);

        if($payment) {
            return response()->json([
                'status' => 'success', 
                'data' => $payment
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
        $payment = Payment::find($id);

        try {
            $payment->update($request->all());

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
        $payment = Payment::find($id);

        if($payment) {
            if($payment->transactions()->count()) {
                return response()->json(['status' => 'restricted']);
            }

            try {
                $payment->delete();
                return response()->json(['status' => 'success']);
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        } 

        return response()->json(['status' => 'not found']);
    }
}
