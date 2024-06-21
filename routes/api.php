<?php

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PatientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('adminlogin', [AdminController::class,'adminlogin'])->name('adminlogin');
    Route::post('Adminregister', [AdminController::class,'Adminregister'])->name('Adminregister');
    Route::post('logoutAdmin', [AdminController::class,'logoutAdmin'])->name('logoutAdmin');
    Route::get('profileAdmin', [AdminController::class,'profileAdmin'])->name('profileAdmin');


    Route::post('Patientlogin', [PatientController::class,'Patientlogin'])->name('Patientlogin');
    Route::post('Patientregister', [PatientController::class,'Patientregister'])->name('Patientregister');
    Route::post('logoutPatient', [PatientController::class,'logoutPatient'])->name('logoutPatient');
    Route::get('profilePatient', [PatientController::class,'profilePatient'])->name('profilePatient');

    Route::post('Doctorlogin', [DoctorController::class,'Doctorlogin'])->name('Doctorlogin');
    Route::post('Doctorregister', [DoctorController::class,'Doctorregister'])->name('Doctorregister');
    Route::post('logoutDoctor', [DoctorController::class,'logoutDoctor'])->name('logoutDoctor');
    Route::get('profileDoctor', [DoctorController::class,'profileDoctor'])->name('profileDoctor');

    Route::post('registerUser', [UserController::class,'registerUser'])->name('registerUser');
    Route::post('loginUser', [UserController::class,'loginUser'])->name('loginUser');

    Route::get('profileUser', [UserController::class,'profileUser'])->name('profileUser');

    Route::post('logoutUser', [UserController::class,'logoutUser'])->name('logoutUser');



    Route::post('createArticle', [ArticleController::class,'createArticle'])->name('createArticle');

    Route::get('getAllArticle', [ArticleController::class,'getAllArticle'])->name('getAllArticle');

    Route::get('getSingleArticle/{id}', [ArticleController::class,'getSingleArticle'])->name('getSingleArticle');

    Route::get('getArticlesDoctor/{id}', [ArticleController::class,'getArticlesDoctor'])->name('getArticlesDoctor');

    Route::delete('deleteArticle/{id}', [ArticleController::class,'deleteArticle'])->name('deleteArticle');






    // petients

    Route::get('getAllPatients', [PatientController::class,'getAllPatients'])->name('getAllPatients');
    Route::post('createBooking', [BookingController::class,'createBooking'])->name('createBooking');

    // get all booking by admin
    Route::get('getAllBooking', [BookingController::class,'getAllBooking'])->name('getAllBooking');


    // get all booking by the doctor
    Route::get('getBookingByDoctor/{id}', [BookingController::class,'getBookingByDoctor'])->name('getBookingByDoctor');


    // get all booking by the doctor
    Route::get('getBookingByPatient/{id}', [BookingController::class,'getBookingByPatient'])->name('getBookingByPatient');


     // get all booking by the patient
     Route::get('getAllPatients', [BookingController::class,'getAllPatients'])->name('getAllPatients');

    // delete the booking
    Route::delete('deleteBooking/{id}', [ArticleController::class,'deleteBooking'])->name('deleteBooking');



    // create the group
    Route::post('createGroup', [GroupController::class,'createGroup'])->name('createGroup');


    // route to read all users


    Route::get('getAllGroups', [GroupController::class,'getAllGroups'])->name('getAllGroups');

    // get group by doctor
    Route::get('getGroupByDoctor/{id}', [GroupController::class,'getGroupByDoctor'])->name('getGroupByDoctor');

    Route::get('getSingleGroup/{id}', [GroupController::class,'getSingleGroup'])->name('getSingleGroup');





    Route::delete('deleteGroup/{id}', [GroupController::class,'deleteGroup'])->name('deleteGroup');

    Route::patch('updateBookingStatus/{id}', [BookingController::class,'updateBookingStatus'])->name('updateBookingStatus');



    // get all doctors

    Route::get('getAllDoctors', [DoctorController::class,'getAllDoctors'])->name('getAllDoctors');


    // delete user

    Route::delete('deleteDoctor/{id}', [DoctorController::class,'deleteDoctor'])->name('deleteDoctor');


    

});
