<?php

namespace App\Http\Controllers\Back;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeptHeadController extends Controller
{      
    public function getAdvisor(){
        $dept_id = Auth::user()->department_id;
        $role_id = 13; 
        $advisor = User::with(['role','department'])->where('role_id',$role_id)->where('department_id',$dept_id)->get();
        return response()->json($advisor);
    }
    public function getStudents(){
        $dept_id = Auth::user()->department_id;
        $role_id = 10; 
        $student = User::with(['role','department'])->where('role_id',$role_id)->where('department_id',$dept_id)->get();
        return response()->json($student);
    }
    public function addAdvisor(Request $request){
        $validatedData = $request->validate([
            'full_name'=>'required|string|min:3',
            'email'=>'required|email|unique:users,email',
             ]);
            
       
        try{
            DB::beginTransaction();
            $user = new User();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->role_id = 13;
            $user->department_id = Auth::user()->department_id;
            $usercode=rand('000000','999999');
            $user->code = $usercode;
            $user->password = Hash::make($usercode);
            $user->save();
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    
    }
    public function updateAdvisor(Request $request,$id){
          $user =  User::where('id',$id)->first();
        $validatedData = $request->validate([
            'full_name'=>'required|string|min:3',
            'email'=>'required|email|unique:users,email,'.$id,
             ]);
            
       
        try{
            DB::beginTransaction();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->role_id = 13;
            $user->department_id = Auth::user()->department_id;
            $usercode=rand('000000','999999');
            $user->code = $usercode;
            $user->password = Hash::make($usercode);
            $user->save();
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
}
