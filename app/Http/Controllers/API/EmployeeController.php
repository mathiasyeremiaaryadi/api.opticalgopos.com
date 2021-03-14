<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Storage;

// Import models
use App\Models\Employee;

// Import resources
use App\Http\Resources\EmployeeCollection;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::orderBy('created_at', 'DESC');
        
        if(request()->keyword != '') {
			$employees = $employees->where('code', 'LIKE', '%' . request()->keyword . '%')
								->orWhere('name', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('phone', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('address', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('date_of_birth', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('email', 'LIKE', '%' . request()->keyword . '%');
		}

        if($employees) {
            return new EmployeeCollection($employees->get());   
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
        $employee_requests = $this->generate_code(new Employee(), $request);

        try {
            Employee::create($employee_requests);
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
        $employee = Employee::find($id);

        if($employee) {            
            return response()->json([
                'status' => 'success', 
                'data' => $employee
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
        $employee = Employee::find($id);

        if($employee) {
            return response()->json([
                'status' => 'success', 
                'data' => $employee
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
        $employee = Employee::find($id);
		
		try {			
			$employee->update($request->only(['code', 'name', 'address', 'date_of_birth', 'email']));

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
        $employee = Employee::find($id);

        if($employee) {
            try {
                $employee->delete();
                return response()->json(['status' => 'success']);
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        } 

        return response()->json(['status' => 'not found']);
    }

    public function update_profile(Request $request, $id) {
		$employee = Employee::find($id);
		
		try {			
			if($request->password) {
				$employee->password = bcrypt($request->password);
			}
			
			if($request->file('image')) {
				$employee->image = $request->file('image')->storeAs('images/employee', $request->name . '.jpg');
			}
			
			$employee->name = $request->name;
			$employee->email = $request->email;
		
            $employee->save();

            return response()->json(['status' => 'success']);
        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }
	}

    public function generate_code($employee_model, $employee_requests) {
        $recent_employee_code = $employee_model::orderBy('created_at', 'DESC')->first();
		
		$last_increment_digits = ($recent_employee_code) ? substr($recent_employee_code->code, -4) : 0;

        $employee_requests = $employee_requests->only(['name', 'phone', 'address', 'date_of_birth', 'email']);
		
		$employee_requests['code'] = 'KRY' . str_pad($last_increment_digits + 1, 4, 0, STR_PAD_LEFT);
		
		$employee_requests['password'] = bcrypt('Opticalgo@POSeMP0221');

        return $employee_requests;
    }
}
