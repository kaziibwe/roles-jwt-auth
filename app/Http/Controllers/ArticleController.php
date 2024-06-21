<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Article;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    //

    public function createArticle(Request $request)
    {

        try {
            $data = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'content' => 'required',

            ]);
            $currentDateTime = date('Y-m-d H:i:s');

            $doctor_id = $request->input('doctor_id');
            $content = $request->input('content');

            $updateData = [
                'doctor_id' => $doctor_id,
                'content' => $content,
                'createdAt' => $currentDateTime,

            ];
            $arcticle = Article::create($updateData);

            if ($arcticle) {
                return response()->json(["arcticle" => $arcticle, 'status' => true], 200);
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



    public function getAllArticle()
    {
        $articles =   Article::all();
        return response()->json(['articles' => $articles]);
    }

    public function getSingleArticle($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'article Not Found']);
        }
        return response()->json(['article' => $article]);
    }


    public function getArticlesDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor Not Found']);
        }
        $articles = $doctor->articles()->get();
        return response()->json([
            'doctor' => $doctor,
            'articles' => $articles
        ]);
    }

    public function deleteBooking($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'booking Not Found']);
        }
        $booking->delete();
        return response()->json(["booking deleted successfully"], 200);
    }

    public function deleteArticle($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'article Not Found']);
        }
        $article->delete();
        return response()->json(["message"=>"article deleted successfully"], 200);
    }


}
