<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{
    public function index(){
        return response()->json(Role::all());
    }

    public function deleteRole($id){
        $role=Role::find($id);
        if($role->delete()){
            return response()->json([
                'message'=>'Role deleted successfully',
            ],200);
         }else{
            return response()->json([
                'message'=>'something wrong happen please try again!',
            ],500); 
         }   
    }
 
public function addRole(Request $request){
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
      
    try{
        DB::beginTransaction();
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
public function showRole($id){
    $role= Role::where('id',$id)->get();
    return response()->json($role);
} 

public function updateRole(Request $request,$id){
    $role =  Role::where('id',$id)->first();
    $validatedData = $request->validate([
        'name'=>'required|string|min:3',
        ]);
        
       try{
        DB::beginTransaction();
        $role->name = $request->name;
        $role->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

}
}
