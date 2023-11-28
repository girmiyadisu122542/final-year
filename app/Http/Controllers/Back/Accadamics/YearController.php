<?php

namespace App\Http\Controllers\Back\Accadamics;

use Exception;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class YearController extends Controller
{
    public function index(){
        return response()->json(Year::all());
    }

    public function getYears(){
        $paginateLimit = request('per_page')? request('per_page') :10;
        $year = Year::latest()->paginate($paginateLimit);
        return response()->json($year);
    }

    public function deleteYear($id){
        $year=Year::find($id);
        if($year->delete()){
            return response()->json([
                'message'=>'Year deleted successfully',
            ],200);
         }else{
            return response()->json([
                'message'=>'something wrong happen please try again!',
            ],500); 
         }   
    }
 
public function addYear(Request $request){
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
      
    try{
        DB::beginTransaction();
        $year = new Year();
        $year->name = $request->name;
        $year->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
public function showYear($id){
    $year= Year::where('id',$id)->get();
    return response()->json($year);
} 

public function updateYear(Request $request,$id){
    $year =  Year::where('id',$id)->first();
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
        
       try{
        DB::beginTransaction();
        $year->name = $request->name;
        $year->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
}
