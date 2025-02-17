<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/testing', function () {
	return response()->json([
		'message' => 'Hello World!'
	]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum', 'throttle:60,1')->group(function () {
	Route::post('/transactions', [TransactionController::class, 'create']);
	Route::get('/transactions', [TransactionController::class, 'index']);
	Route::get('/transactions/{id}', [TransactionController::class, 'show']);
});
