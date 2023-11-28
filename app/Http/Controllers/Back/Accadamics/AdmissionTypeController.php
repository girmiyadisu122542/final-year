<?php

namespace App\Http\Controllers\Back\Accadamics;

use Exception;
use Illuminate\Http\Request;
use App\Models\AdmissionType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdmissionTypeController extends Controller
{
    public function index(){
        return response()->json(AdmissionType::all());
    }

    public function getAdmissionType(){
        $paginateLimit = request('per_page')? request('per_page') :10;
        $admission = AdmissionType::latest()->paginate($paginateLimit);
        return response()->json($admission);
    }

    public function deleteAdmissionType($id){
        $admissionType=AdmissionType::find($id);
        if($admissionType->delete()){
            return response()->json([
                'message'=>'AdmissionType deleted successfully',
            ],200);
         }else{
            return response()->json([
                'message'=>'something wrong happen please try again!',
            ],500); 
         }   
    }
 
public function addAdmissionType(Request $request){
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
      
    try{
        DB::beginTransaction();
        $admissionType = new AdmissionType();
        $admissionType->name = $request->name;
        $admissionType->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
public function showAdmissionType($id){
    $admissionType= AdmissionType::where('id',$id)->get();
    return response()->json($admissionType);
} 

public function updateAdmissionType(Request $request,$id){
    $admissionType =  AdmissionType::where('id',$id)->first();
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
        
       try{
        DB::beginTransaction();
        $admissionType->name = $request->name;
        $admissionType->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
}
