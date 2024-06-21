<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

     //
     public function _construct() {
        $this->middleware('auth:user-api',['except'=>['loginUser','registerUser']]);
   }
    public function registerUser(Request $request){
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        if($user){
            return response()->json([$user,'status'=>true],200);
        }else{
            return response()->json(['status'=>false],200);
        }
    }

    public function loginUser(Request $request){
            $credentials = request(['email', 'password']);



            if (! $token = auth()->guard('user-api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $token;

    }

    public function profileUser()
    {
        return response()->json(auth()->guard('user-api')->user());
    }

    public function logoutUser()
    {
        auth()->guard('user-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
