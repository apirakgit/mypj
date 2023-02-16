<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QRPaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\VinnoController;
use App\Http\Controllers\Admin\VehicleBrandController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\PromotionCampaignController;
use App\Http\Controllers\Admin\PromotionCodeController;
use App\Http\Controllers\Admin\PromotionCodeListController;
use App\Http\Controllers\Admin\PartnerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return $exitCode . ' cleared';
});

Route::group(['domain' => env('APP_URL_TOPUP')], function(){
	Route::get('/', function () {
		return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale() );
	});
});

//Route::resource('/customer-register', CustomerController::class);
Route::get('/customer/check_dup_email', [CustomerController::class, 'check_dup_email']);
Route::get('/customer/check_dup_email_topup', [CustomerController::class, 'check_dup_email_topup']);
Route::get('/customer/check_promotion', [CustomerController::class, 'check_promotion']);
Route::post('/customer/check_promotion_code', [CustomerController::class, 'check_promotion_code']);
Route::get('/customer/getname', [CustomerController::class, 'getName']);
Route::get('/secret', function(){
  $csrf = csrf_token();
  return response()->json($csrf);
});

Route::get('/admin', function () {
    return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale() . "/admin");
});

Route::group(['domain' => env('APP_URL_REGIS')], function(){
  Route::get('/', function () {
      return redirect( env('APP_URL_REGIS') . '/' . app()->getLocale() . "/customer-register");
  });
});

Route::group(['prefix' => '{locale?}','middleware' => 'setlocale'], function () {

  Route::resource('/customer-register', CustomerController::class);

  Route::resource('/topup', OrderController::class);
  Route::resource('/', OrderController::class);
  Route::resource('/payment', PaymentController::class);
  Route::resource('/redirect2c2p', PaymentController::class);
  Route::get('/changeStatus', [App\Http\Controllers\OrderController::class, 'changeStatus'])->name('changeStatus');
  Route::get('/admin/user', [App\Http\Controllers\UserController::class, 'index'])->name('user');

  Route::resource('qr-payment', QRPaymentController::class);
  Route::post('qr-payment-callback', [QRPaymentController::class, 'qr_callback']);

  Auth::routes();

  Route::get('mail_order', function(){
  	return view('email.Ordermail');
  });

  Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\TransactionController::class, 'index'])->name('home');
    Route::get('/admin', [App\Http\Controllers\TransactionController::class, 'index'])->name('admin');
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade');
    Route::get('map', function () {return view('pages.maps');})->name('map');
    Route::get('icons', function () {return view('pages.icons');})->name('icons');
    Route::get('transaction-list', [TransactionController::class,'index'])->name('transaction');
    Route::match(array('GET', 'POST'), 'transaction-list/search', [TransactionController::class,'search'] )->name('transaction-search');

    Route::get('users-list', [UserController::class,'index'])->name('users');
    Route::get('user-edit/{id}', [UserController::class, 'edit']);
    Route::post('user-update', [UserController::class, 'update'])->name('update');
    Route::get('user-search', [UserController::class,'search'])->name('user_search');

    Route::get('dis-user', [UserController::class,'disable_user'])->name('disuser');
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    Route::get('export-transaction',[TransactionController::class, 'exportIntoExcel'])->name('exporttransaction');
    Route::get('search', [TransactionController::class,'search'])->name('search');

    Route::resource('customers', AdminCustomerController::class);
    Route::resource('vinno', VinnoController::class);
    Route::resource('vehicle', VehicleBrandController::class);
    Route::resource('promotion', PromotionController::class);
    Route::resource('partner', PartnerController::class);
    Route::resource('promotion_campaign', PromotionCampaignController::class);
    Route::resource('promotion_code', PromotionCodeController::class);
    Route::get('export-promotioncode/{id}',[PromotionCodeController::class,'exportPromotionCode'])->name('export-promotioncode');
    Route::post('promotion_code_im', [PromotionCodeController::class, 'searchImport']);
    Route::resource('promotion_code_list', PromotionCodeListController::class);

    Route::get('file-import-export/vinno', [VinnoController::class, 'fileImportExport']);
    Route::post('file-import', [VinnoController::class, 'fileImport'])->name('file-import');
    Route::get('file-export', [VinnoController::class, 'fileExport'])->name('file-export');
    Route::get('file-export/customers', [AdminCustomerController::class, 'fileExport'])->name('customer-export');

    Route::get('generate_secret_key', [PartnerController::class, 'generate_secret_key']);
});

});
