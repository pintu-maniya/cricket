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

/*Route::post('/tokens/create', function (Request $request) {
    $token = $request->loginKey->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});*/

Route::post('auth-key', [\App\Http\Controllers\Api\TokenGenerateController::class,'getToken']);
Route::get('tournament', [\App\Http\Controllers\Api\TournamentController::class,'getTournament']);
Route::get('stats', [\App\Http\Controllers\Api\StatsController::class,'getStats']);
Route::get('players', [\App\Http\Controllers\Api\PlayersController::class,'getPlayer']);
Route::get('teams', [\App\Http\Controllers\Api\TeamController::class,'getTeams']);
Route::get('associations-list', [\App\Http\Controllers\Api\AssociationController::class,'getAssociationList']);
Route::get('country-list', [\App\Http\Controllers\Api\CountryController::class,'getCountryList']);
Route::get('matches', [\App\Http\Controllers\Api\MatchesController::class,'getMatches']);
Route::post('ball-by-ball', [\App\Http\Controllers\Api\BallByBollController::class,'getBollByBall']);
Route::post('scorecard', [\App\Http\Controllers\Api\ScoreController::class,'getScoreboard']);
Route::get('ongoing-matches', [\App\Http\Controllers\Api\MatchesController::class,'ongoingMatches']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
