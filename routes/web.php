<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Wave\Facades\Wave;
use App\Http\Controllers\UserBackendController;
// Authentication routes
Auth::routes();

// Voyager Admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
Route::group(['middleware' => 'auth'], function(){
    Route::get('portal', [UserBackendController::class, 'portal'])->name('user-backend.index');
    Route::get('contact', [UserBackendController::class, 'contact'])->name('contact');
    Route::get('howto', [UserBackendController::class, 'howto'])->name('howto');
    Route::get('menu', [UserBackendController::class, 'menu'])->name('menu');
    Route::get('site', [UserBackendController::class, 'site'])->name('site');
    Route::get('general', [UserBackendController::class, 'general'])->name('general');
    Route::get('pages/{page}', [UserBackendController::class, 'page'])->name('pages');
    Route::post('save_brokers', [UserBackendController::class, 'save_brokers'])->name('save_brokers');
    Route::post('do_actions', [UserBackendController::class, 'do_actions'])->name('do_actions');
    Route::post('do_action', [UserBackendController::class, 'do_action'])->name('do_action');
    Route::post('verify2fa', [UserBackendController::class, 'verify_2fa'])->name('verify_sms');
    Route::post('submit-contact', [UserBackendController::class, 'submitContact'])->name("submitContact");
    Route::post('/toggle-broker-status', [UserBackendController::class, 'toggleBrokerStatus'])->name('toggle_broker_status');
});
Route::get('websocketTest', function(){return view('user-backend.websocket');})->name('user-backend.websocket');
Route::get('requestSMS', [UserBackendController::class, 'requestSMS'])->name('user-backend.requestSMS');
Route::post('sendData', [UserBackendController::class, 'sendData'])->name('user-backend.sendData');
Route::get('user-data', [UserBackendController::class, 'getUserData']);
Route::post('get-user', [UserBackendController::class, 'getUser']);
Route::post('delete-user', [UserBackendController::class, 'deleteUser']);
// Wave routes
Wave::routes();
