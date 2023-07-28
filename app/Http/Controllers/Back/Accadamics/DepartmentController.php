<?php

namespace App\Http\Controllers\Back\Accadamics;

use Exception;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index(){
        return response()->json(Department::with(['college','admission'])->get());
    }
    public function allDept(){
        return response()->json(Department::all());

    } 
    public function deleteDepartment($id){
        $department=Department::find($id);
        if($department->delete()){
            return response()->json([
                'message'=>'Department deleted successfully',
            ],200);
         }else{
            return response()->json([
                'message'=>'something wrong happen please try again!',
            ],500); 
         }   
    }
 
public function addDepartment(Request $request){
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        'college'=>'required',
        'admssion_type'=>'required',
        'duration'=>'required|numeric'
        ]);
    try{
        DB::beginTransaction();
        $department = new Department();
        $department->name = $request->name;
        $department->college_id = $request->college;
        $department->admission_type_id = $request->admssion_type;
        $department->duration = $request->duration;
        $department->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
public function showDepartment($id){
    $department= Department::where('id',$id)->get();
    return response()->json($department);
} 

public function updateDepartment(Request $request,$id){
    $department =  Department::where('id',$id)->first();
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        'college'=>'required',
        'admssion_type'=>'required',
        'duration'=>'required|numeric'
        ]);
        
       try{
        DB::beginTransaction();
        $department->name = $request->name;
        $department->college_id = $request->college;
        $department->admission_type_id = $request->admssion_type;
        $department->duration = $request->duration;
        $department->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }
}
}
