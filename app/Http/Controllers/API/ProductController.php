<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\QueryException;

// Import models
use App\Models\Product;

// Import resources
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC');
		
		if(request()->keyword != '') {
			$products = $products->where('code', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('name', 'LIKE', '%' . request()->keyword . '%');
		}

        if($products) {
            return new ProductCollection($products->get());
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
		$product_requests = $this->generate_code(new Product(), $request);
		
        try {
            Product::create($product_requests);
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
        $product = Product::find($id);

        if($product) {
            return response()->json([
                'status' => 'success', 
                'data' => $product
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
        $product = Product::find($id);

        if($product) {
            return response()->json([
                'status' => 'success', 
                'data' => $product
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
        $product = Product::find($id);

        try {
            $product->update($request->all());
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
        $product = Product::find($id);

        if($product) {
            if($product->brands()->count()) {
                return response()->json(['status' => 'restricted']);
            } 

            try {
                $product->delete();
    
                return response()->json(['status' => 'success']);
                
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'not found']);
    }

    public function generate_code($product_model, $product_requests) {
        $recent_product_code = $product_model::orderBy('created_at', 'DESC')->first();
		
		$last_increment_digits = ($recent_product_code) ? substr($recent_product_code->code, -4) : 0;

        $product_requests = $product_requests->all();
		
		$product_requests['code'] = 'PRD' . str_pad($last_increment_digits + 1, 4, 0, STR_PAD_LEFT);

        return $product_requests;
    }
}
