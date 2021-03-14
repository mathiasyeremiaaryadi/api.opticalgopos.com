<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

// Import models
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::find($id);

        if($admin) {
            return response()->json([
                'status' => 'success', 
                'data' => $admin
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
        $admin = Admin::find($id);
		
        try {			
			if($request->password) {
				$admin->password = Hash::make($request->password);
			}
			
			if($request->file('image')) {
				$admin->image = $request->file('image')->storeAs('images/admin', $request->name . '.jpg');
			}
			
			$admin->name = $request->name;
			$admin->email = $request->email;
		
            $admin->save();

            return response()->json(['status' => 'success']);
        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }
    }
}
