<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\GeoBlockingController;
use App\Http\Controllers\LeagueController;
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
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/', function () {
    return redirect()->action([UsersController::class, 'index']);
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::resource('geo-blocking', GeoBlockingController::class);
Route::resource('league', LeagueController::class);
Route::resource('team', \App\Http\Controllers\TeamController::class);
Route::resource('match', \App\Http\Controllers\MatchesController::class);
Route::resource('ads', AdsController::class);

// Route::get('/user.get_data',[UserController::class, 'get_data'])->name('get_data');
Route::resource('users', UsersController::class);
