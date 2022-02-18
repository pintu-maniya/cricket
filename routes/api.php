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

Route::get('matches', [\App\Http\Controllers\Api\MatchesController::class,'getMatches']);
// Home
Route::get('today-matches', [\App\Http\Controllers\Api\MatchesController::class,'getTodayMatches']); // Today's Matches
Route::get('upcoming-matches', [\App\Http\Controllers\Api\MatchesController::class,'upcomingMatches']); // Upcoming Matches
Route::get('stats', [\App\Http\Controllers\Api\StatsController::class,'getStats']); // All Stats

// x - 72
Route::post('scorecard', [\App\Http\Controllers\Api\ScoreController::class,'getScoreboard']); // Scorecard - para {match_key, team_key}

// X - 119
Route::get('ongoing-matches', [\App\Http\Controllers\Api\MatchesController::class,'ongoingMatches']); // Ongoing Matches



Route::post('auth-key', [\App\Http\Controllers\Api\TokenGenerateController::class,'getToken']);
Route::get('tournament', [\App\Http\Controllers\Api\TournamentController::class,'getTournament']);

Route::get('players', [\App\Http\Controllers\Api\PlayersController::class,'getPlayer']);
Route::get('teams', [\App\Http\Controllers\Api\TeamController::class,'getTeams']);
Route::get('associations-list', [\App\Http\Controllers\Api\AssociationController::class,'getAssociationList']);
Route::get('country-list', [\App\Http\Controllers\Api\CountryController::class,'getCountryList']);

Route::post('ball-by-ball', [\App\Http\Controllers\Api\BallByBollController::class,'getBollByBall']);

Route::post('get-stats-by-match', [\App\Http\Controllers\Api\StatsController::class,'getStatsByMatch']);
Route::get('completed-matches', [\App\Http\Controllers\Api\MatchesController::class,'getCompletedMatches']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
