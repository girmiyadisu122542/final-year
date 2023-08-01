<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   
   public function login(Request $request)
   {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
       if(!Auth::attempt(['email' => $request->email,'password' =>$request->password, ]) || $request->user()->status == 0){
        return response()->json([
            'message' => "Invalid Credentials! We can't find this match in our records!"
        ],401);
       }
        $user = $request->user();
        $tokenData = $user->createToken($request->email);
        
        //$token = $tokenData->token;
        return response()->json([
        'access_token' => $tokenData->accessToken,
        ]);   
   }

   public function logout(Request $request){
    $request->user()->token()->revoke();
    return response()->json([
        'message'=>'successfully logout!',
    ],200);
   }

   public function register(Request $request){
       if($request->role!=null && $request->role == 'Student' ){
        $request->validate([
            'college' => 'required',
            'study_level' => 'required',
            'department' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'role' => 'required',
            'full_name' => 'required',
        ]);
       }else{
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'role' => 'required',
            'full_name' => 'required',

        ]);
       }

       try{
        DB::beginTransaction();
        $user = new User();
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->department_id = $request->department;
         if($request->role == 'Student'){
            $user->role_id =10;
        }else if($request->role == 'Researcher'){
             $user->role_id =12;
                     
            }else{
             $user->role_id =16;
             $user->status = 1;
         }
        $user->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
    }

   }
}
