<?php

use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\BookController;
use App\Http\Controllers\api\GenresController;
use App\Http\Controllers\api\PlansController;
use App\Http\Controllers\api\FreeTrialController;
use App\Http\Controllers\api\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// all protected apis
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('create-book', [BookController::class, 'createBook']);
    Route::post('create-genre', [GenresController::class, 'createGenre']);
    Route::post('create-plan', [PlansController::class, 'createPlan']);
    Route::post('subscribe-to-free-trial', [FreeTrialController::class, 'subscribeToFreeTrial']);
    Route::post('check-if-free-trial_over', [FreeTrialController::class, 'checkIfFreeSubscribeIsOver']);
    Route::post('subscribe-to-plan', [SubscriptionController::class, 'subscribeToPlan']);
    Route::post('check -if-subscribe-to-plan', [SubscriptionController::class, 'checkIfSubscribeToPlan']);


});

// all public apis
Route::post('create-user', [UserController::class, 'createUser']);
Route::post('login-user', [UserController::class, 'login']);
Route::get('get-all-books', [BookController::class, 'getAllBooks']);
Route::get('get-all-genres', [GenresController::class, 'getAllGenres']);
Route::get('get-all-plans', [PlansController::class, 'showAllPlans']);

