<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\Transaction;

// Import resources
use App\Http\Resources\TransactionCollection;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::all();

        $last_transactions = Transaction::orderBy('transaction_date', 'DESC')
                                            ->take(10)
                                            ->with('customer') 
                                            ->get();

        if(request()->start_date != '' && request()->last_date != '') {
            $transactions = Transaction::whereBetween('transaction_date', [request()->start_date, request()->last_date])->get();

            $last_transactions = Transaction::whereBetween('transaction_date', [request()->start_date, request()->last_date])
                                                ->orderBy('transaction_date', 'DESC')
                                                ->take(10)
                                                ->with('customer')
                                                ->get();
        }

        if($transactions && $last_transactions) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'transactions' => $transactions,
                    'last_transactions' => $last_transactions
                ]
            ]);
        }   

        return response()->json(['status' => 'not found']); 
    }
}
