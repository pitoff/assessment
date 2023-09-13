<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::resources(['users' => UserController::class]);
    Route::softDeletes();
    Route::get('/user-details/{id}', [UserController::class, 'userDetails'])->name('user.details');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
