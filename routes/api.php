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

Route::post('auth-key', [\App\Http\Controllers\TokenGenerateController::class,'getToken']);
Route::get('tournament', [\App\Http\Controllers\TournamentController::class,'getTournament']);
Route::get('stats', [\App\Http\Controllers\StatsController::class,'getStats']);
Route::get('players', [\App\Http\Controllers\PlayersController::class,'getPlayer']);
Route::get('teams', [\App\Http\Controllers\TeamController::class,'getTeams']);
Route::get('associations-list', [\App\Http\Controllers\AssociationController::class,'getAssociationList']);
Route::get('country-list', [\App\Http\Controllers\CountryController::class,'getCountryList']);
Route::get('matches', [\App\Http\Controllers\MatchesController::class,'getMatches']);
Route::post('ball-by-ball', [\App\Http\Controllers\BallByBollController::class,'getBollByBall']);
Route::post('scorecard', [\App\Http\Controllers\ScoreController::class,'getScoreboard']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
