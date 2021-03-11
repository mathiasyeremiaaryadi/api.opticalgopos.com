<?php

//Selamat siang , ijin bertanya, saya punya database dengan tabel merk, kolomnya adalah nama merk dan produk id yang mengambil id dari tabel produk, bagaimana caranya saya ingin mengambil nama produknya melalui produk id (foreign key) menggunakan eloquent Laravel? Saya sudah coba dengan Merk::with('produk')->where('nama_produk', 'LIKE', '%' . $keyword . '%'); tetapi malah error

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\QueryException;

// Import models
use App\Models\Brand;
use App\Models\Product;

// Import resources
use App\Http\Resources\BrandCollection;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if(request()->keyword != '') {
			$brands = Brand::with('product')->whereHas('product', function($query) {
                            $query->where('product_name', 'LIKE', '%' . request()->keyword . '%');
                        })
                        ->orWhere('name', 'LIKE', '%' . request()->keyword . '%')
                        ->orderBy('created_at', 'DESC')
                        ->get();
		} else if(request()->product != '') {
			$brands = Product::find(request()->product)->brands;
		} else {
			$brands = Brand::with('product')->orderBy('created_at', 'DESC')->get();
		}

        if($brands) {
            return new BrandCollection($brands);
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
            Brand::create($request->all());
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
        $brand = Brand::with('product')->find($id);

        if($brand) {
            return response()->json([
                'status' => 'success', 
                'data' => $brand
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
        $brand = Brand::find($id);

        if($brand) {
            return response()->json([
                'status' => 'success', 
                'data' => $brand
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
        $brand = Brand::find($id);

        try {
            $brand->update($request->all());
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
        $brand = Brand::find($id);

        if($brand->stocks()->count()) {
            return response()->json(['status' => 'restricted']);
        }

        if($brand) {
            try {
                $brand->delete();
                return response()->json(['status' => 'success']);
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'not found']);
    }
}
