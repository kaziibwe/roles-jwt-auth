<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller
{
    //

    public function _construct()
    {
        $this->middleware('auth:doctor-api', ['except' => ['doctorlogin', 'doctorregister']]);
    }
    public function Doctorregister(Request $request)
    {

        // $authenticatedUser = Auth::guard('admin-api')->user();
        // if (!$authenticatedUser) {
        //     return response()->json(['status' => false], 401);
        // }
        try {
            $data = $request->validate([
                'email' => ['required', 'email', Rule::unique('doctors', 'email')],
                "name" => "required | string",
                "sex" => "required | string",
                "location" => "required | string",
                "category" => "required | string",
                "nin" => "required | string",
                "age" => "required | string",
                "phone" => "required | string",
                'password' => 'required',

            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }

            $data['password'] = bcrypt($data['password']);
            $doctor = Doctor::create($data);

            if ($doctor) {
                return response()->json(["doctor" => $doctor, 'status' => true], 200);
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


    public function doctorUpdate(Request $request, $id)
    {

        // $authenticatedUser = Auth::guard('admin-api')->user();
        // if (!$authenticatedUser) {
        //     return response()->json(['status' => false], 401);
        // }

        $user = Doctor::find($id);
        if (!$user) {
            return response()->json(['status' => false], 404);
        }

        try {
            $data = $request->validate([
                'email' => 'email|nullable',
                "name" => "nullable ",
                "sex" => "nullable ",
                "location" => "nullable ",
                "category" => "nullable ",
                "nin" => "nullable ",
                "age" => "nullable ",
                "phone" => "nullable ",
                'password' => 'nullable',

            ]);


            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }

            // Hash the password if it is present in the request
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);

            } else {
                unset($data['password']);
            }

            $user->update($data);

            return response()->json(["message" => "Doctor updated successfully", 'status' => true], 200);
        } catch (ValidationException $e) {
            // Return JSON response with validation errors
            return response()->json([
                'errors' => $e->errors(), // Detailed validation errors
            ], 422);
        } catch (\Exception $e) {
            // Catch any other exceptions and return a generic error response
            return $e->getMessage();
            return response()->json(["message" => "Server error"], 500);
        }
    }



    public function Doctorlogin(Request $request)
    {
        $credentials = request(['email', 'password']);



        if (!$token = auth()->guard('doctor-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $token;
    }

    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => Auth::guard('doctor-api')->factory()->getTTL() * 60
    //     ]);
    // }

    public function profileDoctor()
    {
        return response()->json(auth()->guard('doctor-api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutdoctor()
    {
        auth()->guard('doctor-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }




    public function getAllDoctors()
    {
        $doctors =   Doctor::all();
        return response()->json(['doctors' => $doctors]);
    }


    public function deleteDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'doctor Not Found']);
        }
        $doctor->delete();
        return response()->json(["Doctor deleted successfully"], 200);
    }




    public function getSingleDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'doctor Not Found']);
        }
        return response()->json([
            'doctor' => $doctor
        ]);
    }
}
