<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use App\Models\Subscriptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class SubscriptionController extends Controller
{
    //
    public function subscribeToPlan(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'plan_id' => 'required'
        ]);
        if ($validate->fails()) {
            return response([
                'status' => false,
                'message' => $validate->errors()
            ], 409);
        }
        try {
            $user = auth('sanctum')->user();

            $subscribed = Subscriptions::where('user_id', $user->id)->where('is_completed', 0)->first();

            if ($subscribed) {
                return response([
                    'status' => false,
                    'message' => 'User already subscribed',
                ], 409);
            }

            $plan_id = $request->get('plan_id');
            $plan = Plans::where('id', $plan_id)->first();
            if ($plan->total_months == 1) {
                $sub_start = Carbon::now();
                $sub_end = Carbon::now()->addMonths(1);
            } else if ($plan->total_months == 3) {
                $sub_start = Carbon::now();
                $sub_end = Carbon::now()->addMonths(3);
            } else if ($plan->total_months == 6) {
                $sub_start = Carbon::now();
                $sub_end = Carbon::now()->addMonths(6);
            } else {
                $sub_start = Carbon::now();
                $sub_end = Carbon::now()->addMonths(12);
            }

            $data = Subscriptions::create([
                'plan_id' => $plan_id,
                'user_id' => $user->id,
                'subscription_start_timestamp' => $sub_start,
                'subscription_end_timestamp' => $sub_end,
                'is_completed' => 0
            ]);

            return response([
                'status' => true,
                'message' => 'User subscription success',
                'data' => $data
            ], 201);

        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'subscription fail',
                'error' => $e->getMessage()
            ], 409);
        }

    }


    public function checkIfSubscribeToPlan()
    {
        $user_id = auth('sanctum')->user()->id;
        $subscribed = Subscriptions::where('user_id', $user_id)->where('is_completed', 0)->first();

        if ($subscribed) {
            if (Carbon::now()->lessThanOrEqualTo($subscribed->subscription_end_timestamp)) {
                return response([
                    'status' => true,
                    'message' => 'user subscribed',
                ]);
            } else {
                Subscriptions::where('id', $subscribed->id)->update(['is_completed' => 1]);
                return response([
                    'status' => true,
                    'message' => 'user subscription ends',
                ]);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'no subscription found',
            ],409);
        }

    }
}
