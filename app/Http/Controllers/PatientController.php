<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth:patient-api', ['except' => ['patientlogin', 'patientregister']]);
    }
    public function Patientregister(Request $request)
    {

        try {
            $data = $request->validate([
                "name" => "required | string",
                'email' => ['required', 'email', Rule::unique('patients', 'email')],
                "sex" => "required | string",
                "nin" => "required | string",
                "age" => "required | string",
                "phone" => "required | string",
                'password' => 'required',

            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            $data['password'] = bcrypt($data['password']);
            $patient = Patient::create($data);


            if ($patient) {
                return response()->json(["patient" => $patient, 'status' => true], 200);
            } else {
                return response()->json(['status' => false], 500);
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



    public function patientUpdate(Request $request, $id)
    {

        $user = Patient::find($id);
        
        if (!$user) {
            return response()->json(['status' => false], 200);
        }
        try {
            $data = $request->validate([
                "name" => "nullable ",
                'email' =>"nullable|email",
                "sex" => "nullable ",
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

             Patient::where('id', $id)->update($data);

            return response()->json(["message" => "Patient updated successfully", 'status' => true], 200);
        } catch (ValidationException $e) {
            // Return JSON response with validation errors
            return response()->json([
                'errors' => $e->errors(), // Detailed validation errors
            ], 422);
        } catch (\Exception $e) {
            return $e->getMessage();
            // Catch any other exceptions and return a generic error response
            return response()->json(["message" => "Server error"], 200);
        }
    }


    // public function patientUpdate(Request $request, $id)
    // {

    //     $user = Patient::find($id);
    //     if (!$user) {
    //         return response()->json(['status' => false], 404);
    //     }
    //     try {
    //         $data = $request->validate([
    //             "name" => "nullable ",
    //             'email' => 'nullable | email',
    //             "sex" => "nullable ",
    //             "nin" => "nullable ",
    //             "age" => "nullable ",
    //             "phone" => "nullable ",
    //             'password' => 'nullable',

    //         ]);

    //         if ($request->hasFile('image')) {
    //             $data['image'] = $request->file('image')->store('images', 'public');
    //         }
    //         $data['password'] = bcrypt($data['password']);

    //         $patient = Patient::where('id', $id)->update($data);

    //         return response()->json(["message" => "Patient updated successfully", 'status' => true], 200);
    //     } catch (ValidationException $e) {
    //         // Return JSON response with validation errors
    //         return response()->json([
    //             'errors' => $e->errors(), // Detailed validation errors
    //         ], 422);
    //     } catch (\Exception $e) {
    //         // Catch any other exceptions and return a generic error response
    //         return response()->json(["message" => "Server error"], 200);
    //     }
    // }

    public function Patientlogin(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard('patient-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $token;
    }

    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => Auth::guard('patient-api')->factory()->getTTL() * 60
    //     ]);
    // }

    public function profilePatient()
    {
        return response()->json(auth()->guard('patient-api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutpatient()
    {
        auth()->guard('patient-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }




    public function getAllPatients()
    {

        $patients = Patient::all();
        return response()->json(['patients' => $patients]);
    }

    //    public function getSingleArticle($id){
    //         $article = Article::find($id);
    //         if(!$article){
    //            return response()->json(['message'=>'article Not Found']);
    //         }
    //         return response()->json(['article'=>$article]);

    //    }


    public function getSinglePatient($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'patient Not Found']);
        }
        return response()->json([
            'patient' => $patient
        ]);
    }
}
