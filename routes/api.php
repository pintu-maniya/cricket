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
// Home & X-74
Route::get('today-matches', [\App\Http\Controllers\Api\MatchesController::class,'getTodayMatches']); // Today's Matches
Route::get('upcoming-matches', [\App\Http\Controllers\Api\MatchesController::class,'upcomingMatches']); // Upcoming Matches
Route::get('stats', [\App\Http\Controllers\Api\StatsController::class,'getStats']); // All Stats
Route::get('tournament', [\App\Http\Controllers\Api\TournamentController::class,'getTournament']); // All Tournament

// X-72
Route::post('scorecard', [\App\Http\Controllers\Api\ScoreController::class,'getScoreboard']); // Scorecard - para {match_key, team_key}

// X-119
Route::get('ongoing-matches', [\App\Http\Controllers\Api\MatchesController::class,'ongoingMatches']); // Ongoing Matches

// X-50
Route::get('completed-matches', [\App\Http\Controllers\Api\MatchesController::class,'getCompletedMatches']); // Shedule

// X-63
Route::post('ball-by-ball', [\App\Http\Controllers\Api\BallByBollController::class,'getBollByBall']); // Commentary - Para{match_key, previous_over_key}

// X-74
Route::post('get-stats-by-match', [\App\Http\Controllers\Api\StatsController::class,'getStatsByMatch']); // Stats - Para{match_key}

// X-50
Route::get('overview', [\App\Http\Controllers\Api\MatchesController::class,'getOverview']); // Not Completed api


Route::post('auth-key', [\App\Http\Controllers\Api\TokenGenerateController::class,'getToken']);


Route::get('players', [\App\Http\Controllers\Api\PlayersController::class,'getPlayer']); // Not Completed api

Route::get('teams', [\App\Http\Controllers\Api\TeamController::class,'getTeams']);
Route::get('associations-list', [\App\Http\Controllers\Api\AssociationController::class,'getAssociationList']);
Route::get('country-list', [\App\Http\Controllers\Api\CountryController::class,'getCountryList']); // added by pintu, get country form api
Route::get('country', [\App\Http\Controllers\Api\CountryController::class,'country']);// added by pintu, get country from db
//Route::get('matches', [\App\Http\Controllers\Api\MatchesController::class,'getMatches']); //



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
