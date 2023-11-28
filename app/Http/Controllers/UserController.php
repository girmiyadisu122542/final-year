<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'department'])->get();
        return response()->json($users);
    }
    public function getUsers(){
        $paginateLimit = request('per_page')? request('per_page') :10;
        $user = User::with(['role', 'department'])->latest()->paginate($paginateLimit);
        return response()->json($user);
    }
    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user->delete()) {
            return response()->json([
                'message' => 'user deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'something wrong happen please try again!',
            ], 500);
        }
    }
    public function changeStatus($id)
    {
        $user = User::find($id);

        ($user->status == 0) ? $user->status = 1 : $user->status = 0;
        if ($user->save()) {
            return response()->json([
                'message' => 'User status Changed!',
            ], 200);
        } else {
            return response()->json([
                'message' => 'something wrong happen please try again!',
            ], 500);
        }
    }
    public function addUser(Request $request)
    {
        if ($request->role == 1 || $request->role == 12 || $request->role == 14) {
            $request->validate([
                'full_name' => 'required|string|min:3',
                'email' => 'required|email',
                'role' => 'required',
                'department' => 'required',
            ]);
        } else {
            $request->validate([
                'full_name' => 'required|string|min:3',
                'email' => 'required|email',
                'role' => 'required',
            ]);
        }

        try {
            DB::beginTransaction();
            $user = new User();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->role_id = $request->role;
            $user->department_id = $request->department;
            $usercode = rand('000000', '999999');
            $user->code = $usercode;
            $user->password = Hash::make($usercode);
            $user->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    public function showUser($id)
    {
        $user = User::where('id', $id)->get();
        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $user =  User::where('id', $id)->first();
        $validatedData = $request->validate([
            'full_name' => 'required|string|min:3',
            'email' => 'required|email',
            'role' => 'required',
            'department' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->role_id = $request->role;
            $user->department_id = $request->department;
            $usercode = rand('000000', '999999');
            $user->code = $usercode;
            $user->password = Hash::make($usercode);
            $user->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    public function getUserRole(){
        $user_id = Auth::user()->id;
        $user =  User::where('id',$user_id);
        //dd($user);
        // if($user!=null){
        //     return response()->json($user);
        // }else{
        //     return response()->json([
        //         'message' => 'Not Authenticated'
        //     ]);
        // }
    }
}
