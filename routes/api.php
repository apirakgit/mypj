<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerAPIController;
use App\Http\Controllers\QRPaymentController;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('customers-regis', [CustomerAPIController::class, 'store']);
Route::post('customers-list', [CustomerAPIController::class, 'index']);
Route::post('qr-payment-getstatus', [QRPaymentController::class,'get_transaction']);
Route::post('qr-payment-response', [QRPaymentController::class,'qr_response']);
