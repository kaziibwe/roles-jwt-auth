<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    //
    public function createGroup(Request $request){

        try{
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            "link"=>"required | string",
            'title'=>'required',
            'description'=>'required',
        ]);


        $group=Group::create($data);

        if($group){
            return response()->json(["group"=>$group,'status'=>true],200);
        }else{
            return response()->json(['status'=>false],200);
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






public function getAllGroups()
{
    $Groups =   Group::all();
    return response()->json(['groups' => $Groups]);
}

public function getGroupByDoctor($id)
{
    $doctor = Doctor::find($id);
    if (!$doctor) {
        return response()->json(['message' => 'Doctor Not Found']);
    }

    $group = $doctor->groups()->get();
    return response()->json([
        'doctor'=>$doctor,
        'group' => $group
    ]);
}





public function getSingleGroup($id)
{
    $group = Group::find($id);
    if (!$group) {
        return response()->json(['message' => 'group Not Found']);
    }
    return response()->json([
        'group' => $group
    ]);
}



public function deleteGroup($id)
{
    $group = Group::find($id);
    if (!$group) {
        return response()->json(['message' => 'group Not Found']);
    }
    $group->delete();
    return response()->json(["Booking deleted successfully"], 200);
}




}



