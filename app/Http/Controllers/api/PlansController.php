<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use Illuminate\Http\Request;
use Validator;

class PlansController extends Controller
{
    //
    public function createPlan(Request $request)
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
            'total_months' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return response([
                'status' => false,
                'message' => $validate->errors()
            ], 409);
        }

        try {
            $name = $request->get('name');
            $total_months = $request->get('total_months');
            $price = $request->get('price');

            $plan = Plans::create([
                'name' => $name,
                'total_months' => $total_months,
                'price' => $price
            ]);

            return response([
                'status' => true,
                'message' => 'Plan create success',
                'data' => $plan
            ], 201);

        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Plan create fail',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function showAllPlans()
    {
        try {
            return response([
                'status' => true,
                'message' => 'fetch plans success',
                'data' => Plans::all()
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'fetch plans fail',
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
