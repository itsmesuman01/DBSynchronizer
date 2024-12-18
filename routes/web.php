<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleDriveController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['prefix' => 'google'], function(){
    Route::any('/auth', [GoogleDriveController::class, 'redirectToGoogle']);
    Route::any('/callback', [GoogleDriveController::class, 'handleGoogleCallback']);
});
