<?php

namespace App\Http\Controllers\Back\Accadamics;

use Exception;
use App\Models\StudyLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StudyLevelController extends Controller
{
    public function index(){
        return response()->json(StudyLevel::all());
    }

    public function deleteStudyLevel($id){
        $studyLevel=StudyLevel::find($id);
        if($studyLevel->delete()){
            return response()->json([
                'message'=>'StudyLevel deleted successfully',
            ],200);
         }else{
            return response()->json([
                'message'=>'something wrong happen please try again!',
            ],500); 
         }   
    }
 
public function addStudyLevel(Request $request){
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
      
    try{
        DB::beginTransaction();
        $studyLevel = new StudyLevel();
        $studyLevel->name = $request->name;
        $studyLevel->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
public function showStudyLevel($id){
    $studyLevel= StudyLevel::where('id',$id)->get();
    return response()->json($studyLevel);
} 

public function updateStudyLevel(Request $request,$id){
    $studyLevel =  StudyLevel::where('id',$id)->first();
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
        
       try{
        DB::beginTransaction();
        $studyLevel->name = $request->name;
        $studyLevel->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
}
