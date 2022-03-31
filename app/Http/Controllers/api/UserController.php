<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    //
    public function createUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'phone_no' => 'required|min:10|max:10',
            'fcm_token' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'status' => false,
                'message' => $validate->errors()
            ], 409);
        }

        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');
        $phone_no = $request->get('phone_no');
        $fcm_token = $request->get('fcm_token');

        try {


            $user = Users::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'phone_no' => $phone_no,
                'fcm_token' => $fcm_token
            ]);

            //if user table is empty we will assign admin role to first user
            $userCount = count(json_decode(Users::all()));
            if ($userCount == 1) {
                $user->assignRole('admin');
            }
            $token = $user->createToken('my-app-token')->plainTextToken;
            return response([
                'status' => true,
                'message' => 'user create success',
                'data' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'user create fail',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'status' => false,
                'message' => $validate->errors()
            ], 409);
        }

        $email = $request->get('email');
        $password = $request->get('password');

        try {
            $user = Users::where('email', $email)->first();

            //if credentials dont match
            if (!$user || !Hash::check($password, $user->password)) {
                return response([
                    'success' => false,
                    'message' => 'These credentials do not match our records.',
                ], 409
                );
            }

            $token = $user->createToken('my-app-token')->plainTextToken;
            return response([
                    'success' => true,
                    'message'=>'Login success',
                    'data' => $user,
                    'token' => $token,
                ]
            );
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Payment type create fail',
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
