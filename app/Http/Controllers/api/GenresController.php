<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Genres;
use Illuminate\Http\Request;
use Validator;

class GenresController extends Controller
{
    //
    public function createGenre(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user->hasRole('admin')) {
            return response(
                [
                    'status' => false,
                    'message' => 'Unauthenticated',
                ]
            );
        }

        $validate = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'status' => false,
                'message' => $validate->errors()
            ], 409);
        }
        try {
            $name = $request->get('name');
            $genre = Genres::create([
                'name' => $name
            ]);
            return response([
                'status' => true,
                'message' => 'Genre create success',
                'data' => $genre
            ], 201);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Genre create fail',
                'error' => $e->getMessage()
            ], 409);
        }
    }


    public function getAllGenres()
    {
        try {
            return response([
                'status' => true,
                'message' => 'Genres fetch success',
                'data' => Genres::all()
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Genre create fail',
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
