<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Booking;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    //

    public function createBooking(Request $request)
    {

        try {
            $data = $request->validate([
                'patient_id' => 'required|exists:doctors,id',
                'doctor_id' => 'required|exists:doctors,id',
                'session_date' => 'required',

            ]);
            $status ="pending";

            $patient_id = $request->input('patient_id');
            $doctor_id = $request->input('doctor_id');
            $session_date = $request->input('session_date');

            // $patient = Patient::find($patient_id);
            // if(!$patient){
            //     return response()->json(['message'=>'patient not found']);
            // }
            // $doctor = Doctor::find($doctor_id);

            // if(!$doctor){
            //     return response()->json(['message'=>'doctor not found']);
            // }


            $updateData = [
                'doctor_id' => $doctor_id,
                'patient_id' => $patient_id,
                'session_date' => $session_date,
                'status' => $status,

            ];
            $booking = Booking::create($updateData);

            if ($booking) {
                return response()->json(["booking" => $booking, 'status' => true], 200);
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




    // public function getAllBooking()
    // {
    //     $Bookings =   Booking::all();
    //     return response()->json(['Bookings' => $Bookings]);
    // }

    public function getBookingByDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor Not Found']);
        }

        $booking = $doctor->bookings()->get();
        return response()->json([
            'doctor'=>$doctor,
            'booking' => $booking
        ]);
    }


    public function getBookingByPatient($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient Not Found']);
        }

        $booking = $patient->bookings()->get();
        return response()->json([
            'patient'=>$patient,
            'booking' => $booking
        ]);
    }



    public function getBookingsDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor Not Found']);
        }
        $Bookings = $doctor->Bookings()->get();
        return response()->json([
            'doctor' => $doctor,
            'Bookings' => $Bookings
        ]);
    }

    public function deleteBooking($id)
    {
        $Booking = Booking::find($id);
        if (!$Booking) {
            return response()->json(['message' => 'Booking Not Found']);
        }
        $Booking->delete();
        return response()->json(["Booking deleted successfully"], 200);
    }

    public function updateBookingStatus(Request $request,$id){


        $Booking = Booking::find($id);
        if (!$Booking) {
            return response()->json(['message' => 'Booking Not Found']);
        }

        $data = $request->validate([
            "status"=>"required | string",

        ]);

        $group=Booking::where('id',$id)->update($data);

        if($group){
            return response()->json(["message"=>"booking updated successfully",'status'=>true],200);
        }else{
            return response()->json(['status'=>false],200);
        }

    }


}
