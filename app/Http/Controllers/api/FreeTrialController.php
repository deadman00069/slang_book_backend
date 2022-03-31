<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Freetrials;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class FreeTrialController extends Controller
{
    //
    public function subscribeToFreeTrial()
    {
        try {
            $user_id = auth('sanctum')->user()->id;

            //if user already exist
            $check_user = Freetrials::where('user_id', $user_id)->first();
            if ($check_user) {
                return response([
                    'status' => false,
                    'message' => 'User already subscribed to free trial'
                ], 409);
            }

            $free_trial = Freetrials::create([
                'user_id' => $user_id,
                'freetrial_start_timestamp' => Carbon::now(),
                'freetrial_end_timestamp' => Carbon::now()->addDay(7),
            ]);

            return response([
                'status' => true,
                'message' => 'User subscribed to free trial'
                , 'data' => $free_trial
            ]);
        } catch (Exception $e) {
            return response([
                'status' => false,
                'message' => 'User subscribed to free trial fail',
                'error' => $e->getMessage()
            ], 409);
        }
    }


    public function checkIfFreeSubscribeIsOver()
    {
        try {
            $user_id = auth('sanctum')->user()->id;
            $data = Freetrials::where('user_id', $user_id)->first();
            if ($data) {
                $starting_date = $data->freetrial_start_timestamp;
                $ending_date = $data->freetrial_end_timestamp;
                if (Carbon::now()->between($starting_date, $ending_date)) {
                    return response([
                        'status' => true,
                        'message' => 'User has not over his free trial'
                    ]);
                } else {
                    return response([
                        'status' => false,
                        'message' => 'User has over his free trial'
                    ], 409);
                }
            } else {
                return response([
                    'status' => false,
                    'message' => 'User has not subscribe to free trial'
                ], 409);
            }
        } catch (Exception $e) {
            return response([
                'status' => false,
                'message' => 'User subscribed to free trial fail',
                'error' => $e->getMessage()
            ], 409);
        }

    }
}
