<?php

namespace App\Http\Controllers\Back\Accadamics;

use Exception;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CollegeController extends Controller
{

    public function getColleges(){
        $paginateLimit = request('per_page')? request('per_page') :10;
        $college = College::latest()->paginate($paginateLimit);
        return response()->json($college);
    }
    public function index(){
        return response()->json(College::all());
    }

    public function deleteCollege($id){
        $college=College::find($id);
        if($college->delete()){
            return response()->json([
                'message'=>'College deleted successfully',
            ],200);
         }else{
            return response()->json([
                'message'=>'something wrong happen please try again!',
            ],500); 
         }   
    }
 
public function addCollege(Request $request){
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
      
    try{
        DB::beginTransaction();
        $college = new College();
        $college->name = $request->name;
        $college->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
public function showCollege($id){
    $college= College::where('id',$id)->get();
    return response()->json($college);
} 

public function updateCollege(Request $request,$id){
    $college =  College::where('id',$id)->first();
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
        
       try{
        DB::beginTransaction();
        $college->name = $request->name;
        $college->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
}
