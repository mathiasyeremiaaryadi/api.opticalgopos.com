<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

// Models
use App\Models\Employee;
use App\Models\Admin;

class AuthController extends Controller
{
    public function login(Request $request) {
		$admin = Admin::where('email', $request->email)->first();
        $employee = Employee::where('email', $request->email)->first();
	
		if($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('pos-opticalgo-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
            ]);
        }
	
        if($employee && Hash::check($request->password, $employee->password)) {
            $token = $employee->createToken('pos-opticalgo-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
           ]);
        }

        return response()->json([
            'status' => 'not match',
        ]);
    }

    public function show() {
        $user = request()->user();

        $user['role'] = $user->code ? 1 : 0;

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
