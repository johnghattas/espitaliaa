<?php

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

Route::middleware('auth:doctor')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('doctor/')->group( function () {
    Route::get('tables', [App\Http\Controllers\DoctorController::class, 'getTables']);
    Route::patch('appoints', [App\Http\Controllers\DoctorController::class, 'insertOrUpdate']);
    Route::get('reserve', [App\Http\Controllers\DoctorController::class, 'showAllReservations']);
    Route::patch('changeState', [App\Http\Controllers\DoctorController::class, 'changeState']);
});

Route::prefix('user/auth/')->group( function () {
    Route::post('register', [App\Http\Controllers\Auth\PatientAuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Auth\PatientAuthController::class, 'login']);
    Route::post('refresh', [App\Http\Controllers\Auth\PatientAuthController::class, 'refresh']);
    Route::post('logout', [App\Http\Controllers\Auth\PatientAuthController::class, 'logout']);

    Route::get('me', [App\Http\Controllers\Auth\PatientAuthController::class, 'me']);
});

Route::prefix('/doctor/auth/')->group( function () {
    Route::post('register', [App\Http\Controllers\Auth\DoctorAuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Auth\DoctorAuthController::class, 'login']);
    Route::post('refresh', [App\Http\Controllers\Auth\DoctorAuthController::class, 'refresh']);
    Route::post('logout', [App\Http\Controllers\Auth\DoctorAuthController::class, 'logout']);

    Route::get('me', [App\Http\Controllers\Auth\DoctorAuthController::class, 'me']);
});


Route::group([
    'prefix'   => 'patient/',
], function () {
    Route::post('reserve', [App\Http\Controllers\PatientController::class, 'doctorReservation']);
    Route::post('cancel', [App\Http\Controllers\PatientController::class, 'disposeReserv']);
    Route::get('reserves', [App\Http\Controllers\PatientController::class, 'showAllReservations']);
});
Route::get('showDisap/{email}', [App\Http\Controllers\GuestController::class, 'showDisap']);
Route::get('getDoctors', [App\Http\Controllers\GuestController::class, 'getAllDoctors']);
