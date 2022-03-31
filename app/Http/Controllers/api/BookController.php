<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Books;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class BookController extends Controller
{
    //
    public function createBook(Request $request)
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
            'author' => 'required',
            'short_desc' => 'required',
            'genre_id' => 'required|numeric',
            'book_preview' => 'required|',
            'book' => 'required|file'
        ]);

        if ($validate->fails()) {
            return response([
                'status' => false,
                'message' => $validate->errors()
            ], 409);
        }

        try {


            $name = $request->get('name');
            $author = $request->get('author');
            $short_desc = $request->get('short_desc');
            $genre_id = $request->get('genre_id');
            $file = $request->file('book_preview');
            $file2 = $request->file('book');
            $fileName = Carbon::now() . '.' . $file->getClientOriginalExtension();
            $file2Name = Carbon::now() . '.' . $file2->getClientOriginalExtension();
            $path = public_path('/uploads/images/book_previews/');
            $path2 = public_path('/uploads/books/');
            $file->move($path, $fileName);
            $file2->move($path2, $file2Name);

            $book = Books::create([
                'genres_id' => $genre_id,
                'name' => $name,
                'author' => $author,
                'short_desc' => $short_desc,
                'book_preview_url' => '/uploads/images/book_previews/' . $fileName,
                'book_url' => '/uploads/books/' . $file2Name,
            ]);

            return response([
                'status' => true,
                'message' => 'book create success',
                'data' => $book
            ], 201);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'book create fail',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function getAllBooks()
    {
        try {
            return response([
                'success' => true,
                'message' => 'book fetch success',
                'data' => Books::all()
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'book create fail',
                'error' => $e->getMessage()
            ], 409);
        }

    }
}
