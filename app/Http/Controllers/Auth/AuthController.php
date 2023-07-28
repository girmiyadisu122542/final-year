<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   
   public function login(Request $request)
   {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
       if(!Auth::attempt(['email' => $request->email,'password' =>$request->password ])){
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
}
