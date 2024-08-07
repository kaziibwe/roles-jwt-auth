<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Admin;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth:admin-api', ['except' => ['adminlogin', 'adminregister']]);
    }
    public function Adminregister(Request $request)
    {

        try {
            $data = $request->validate([
                "name" => "required | string",
                'email' => ['required', 'email', Rule::unique('admins', 'email')],
                "phone" => "required | string",
                'password' => 'required',

            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            $data['password'] = bcrypt($data['password']);
            $admin = Admin::create($data);

            if ($admin) {
                return response()->json(["admin" => $admin, 'status' => true], 200);
            } else {
                return response()->json(['status' => false], 200);
            }
        } catch (ValidationException $e) {
            // Return JSON response with validation errors
            return response()->json([
                'errors' => $e->errors(), // Detailed validation errors
            ], 422);
        } catch (\Exception $e) {
            // Catch any other exceptions and return a generic error response
            return response()->json([
                'error' => $e->getMessage(), // Detailed error message
            ], 500);
        }
    }



    public function adminUpdate(Request $request, $id)
    {

        try {
            $user = Admin::find($id);
            // return  $request->all();


            if (!$user) {
                return response()->json(['status' => false], 200);
            }
            $data = $request->validate([
                "name" => "nullable ",
                'email' => "nullable|email",
                "phone" => "nullable ",
                'password' => 'nullable',

            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);

            } else {
                unset($data['password']);
            }

            Admin::where('id', $id)->update($data);

            return response()->json(["message" => "Admin updated successfully", 'status' => true], 200);
        } catch (ValidationException $e) {
            // Return JSON response with validation errors
            return response()->json([
                'errors' => $e->errors(), // Detailed validation errors
            ], 422);
        } catch (\Exception $e) {
            // Catch any other exceptions and return a generic error responsee

            return $e->getMessage();
            return response()->json(["message" => "Server error"], 200);
        }
    }

    public function adminlogin(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard('admin-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $token;
    }

    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => Auth::guard('admin-api')->factory()->getTTL() * 60
    //     ]);
    // }

    public function profileAdmin()
    {
        return response()->json(auth()->guard('admin-api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutAdmin()
    {
        auth()->guard('admin-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }



    public function getSingleAdmin($id)
    {
        $Admin = Admin::find($id);
        if (!$Admin) {
            return response()->json(['message' => 'Admin Not Found']);
        }
        return response()->json([
            'Admin' => $Admin
        ]);
    }
}
