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
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProfileController;

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
    Route::get('pages/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/card/update', [PaymentsController::class, 'cardUpdate'])->name('cards.update');
    Route::post('/profile/payment/store', [ProfileController::class, 'storePayment'])->name('profile.storePayment');
    Route::get('/profile/chats', [ProfileController::class, 'viewChats'])->name('profile.viewChats');
    Route::post('/profile/message/send', [ProfileController::class, 'sendMessage'])->name('profile.sendMessage');
    Route::get('/profile/chat/{chatId}/messages', [ProfileController::class, 'getMessages'])->name('profile.getMessages');
    Route::get('user-lookup', [ProfileController::class, 'userLookup'])->name('user.lookup');
    Route::get('general', [UserBackendController::class, 'general'])->name('general');
    Route::get('pages/{page}', [UserBackendController::class, 'page'])->name('pages');
    Route::post('save_brokers', [UserBackendController::class, 'save_brokers'])->name('save_brokers');
    Route::post('do_actions', [UserBackendController::class, 'do_actions'])->name('do_actions');
    Route::post('admin_do_actions', [UserBackendController::class, 'admin_do_actions'])->name('admin_do_actions');
    Route::post('do_action', [UserBackendController::class, 'do_action'])->name('do_action');
    Route::post('verify2fa', [UserBackendController::class, 'verify_2fa'])->name('verify_sms');
    Route::post('submit-contact', [UserBackendController::class, 'submitContact'])->name("submitContact");
    Route::post('/toggle-broker-status', [UserBackendController::class, 'toggleBrokerStatus'])->name('toggle_broker_status');
});

Route::get('websocketTest', function(){return view('user-backend.websocket');})->name('user-backend.websocket');
Route::get('requestSMS', [UserBackendController::class, 'requestSMS'])->name('user-backend.requestSMS');
Route::post('sendData', [UserBackendController::class, 'sendData'])->name('user-backend.sendData');
Route::get('user-data', [UserBackendController::class, 'getUserData']);
Route::get('/edit-scheduled/{id}', [UserBackendController::class, 'editScheduled']);
Route::post('/update-scheduled/{id}', [UserBackendController::class, 'updateScheduled']);
Route::delete('/delete-scheduled/{id}', [UserBackendController::class, 'deleteScheduled']);
Route::post('get-user', [UserBackendController::class, 'getUser'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('create-pending-sms', [UserBackendController::class, 'createPendingSms'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('delete-user', [UserBackendController::class, 'deleteUser'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Wave routes
Wave::routes();
