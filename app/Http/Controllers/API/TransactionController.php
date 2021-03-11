<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\Transaction;
use App\Models\Customer;

// Import resources
use App\Http\Resources\TransactionCollection;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->keyword != '') {
			$transactions = Transaction::with('payment')
							->whereHas('payment', function($query) {
								$query->where('name', 'LIKE', '%' . request()->keyword . '%');
							})
							->orWhere('code', 'LIKE', '%' . request()->keyword . '%')
							->orWhere('lens_type', 'LIKE', '%' . request()->keyword . '%')
							->orWhere('total', 'LIKE', '%' . request()->keyword . '%')
							->orWhere('status', 'LIKE', '%' . request()->keyword . '%');
		} else {
			$transactions = Transaction::with('payment');
		}

        if($transactions) {
            return new TransactionCollection($transactions->orderBy('created_at', 'DESC')->get());
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
        $customer_requests = $this->generate_customer_code(new Customer(), $request);

		try {
            $created_customer = Customer::create($customer_requests);

            $transaction_requests = $this->generate_transaction_code(new Transaction(), $request);

            $transaction_requests['customers_id'] = $created_customer->id;

            Transaction::create($transaction_requests);

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
        $transaction = Transaction::with(['payment', 'customer', 'category'])->first();

        if($transaction) {
            return response()->json([
                'status' => 'success', 
                'data' => $transaction
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
        $transaction = Transaction::with('category')->find($id);

        if($transaction) {
            return response()->json([
                'status' => 'success', 
                'data' => $transaction
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
        $transaction = Transaction::find($id);

        try {
            $transaction->update($request->all());
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
        $transaction = Transaction::find($id);

        if($transaction) {
            try {
                $transaction->delete();
                return response()->json(['status' => 'success']);
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'not found']);
    }

    public function generate_customer_code($customerModel, $customer_requests) {
        $recent_customer_code = $customerModel::orderBy('created_at', 'DESC')->first();

		$last_increment_digits = $recent_customer_code ? substr($recent_customer_code->code, -4) : 0;
		
        $customer_requests = $customer_requests->customer;
		
		$customer_requests['code'] = 'PLG' . str_pad($last_increment_digits + 1, 4, 0, STR_PAD_LEFT);

        return $customer_requests;
    }

    public function generate_transaction_code($transactionModel, $transaction_requests) {
        $recent_transaction_code = $transactionModel::orderBy('created_at', 'DESC')->first();

		$last_increment_digits = ($recent_transaction_code) ? substr($recent_transaction_code->code, -4) : 0;

        $transaction_requests = $transaction_requests->except('customer');
				
		$transaction_requests['code'] = 'TRX' . str_pad($last_increment_digits + 1, 4, 0, STR_PAD_LEFT);

        return $transaction_requests;
    }
}
