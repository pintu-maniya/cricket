<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class MatchesController extends Controller
{
    protected $url = 'featured-matches/';

    public function getMatches()
    {
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $result = $this->prepareMatchesData($token);
        return response()->success($result, "Matches get succssfully");
    }

    public function prepareMatchesData($token)
    {
        $apiResult = sendRequest($token, $this->url);
        $result = [];
        try{
            foreach ($apiResult['matches'] as $row) {
                $team_a_score = $team_b_score = '';
                if (is_null($row['toss'])) {
                    $team_a = $row['teams']['a']['name'];
                    $team_b = $row['teams']['b']['name'];
                } else {
                    $team_a_score = $row['play']['innings_order'][0];
                    $team_b_score = $row['play']['innings_order'][1];
                    $team_a = $row['teams'][str_replace('_1', '', $row['play']['innings_order'][0])]['name'];
                    $team_b = $row['teams'][str_replace('_1', '', $row['play']['innings_order'][1])]['name'];
                    $team_a_score = $row['play']['innings'][$team_a_score]['score_str'];
                    $team_b_score = $row['play']['innings'][$team_b_score]['score_str'];
                }
                // get top piek
                $piek = [];
                if($row['players']){
                    foreach ($row['players'] as $playercode => $player){
                        $piek[$player['player']['name']] = [
                            'player_name' => $player['player']['name'],
                            'country' => $player['player']['nationality']['name'],
                            'score'  => !empty($player['score'][1]) ? !empty($player['score'][1]['batting']) ? $player['score'][1]['batting']['score'] : null : null,
                            'highest_score'  => !empty($player['score'][1]) ? !empty($player['score'][1]['batting']) ? $player['score'][1]['batting']['score']['runs'] : null : null,
                        ];
                    }
                }
                $piek = collect($piek)->sortBy('highest_score')->reverse()->toArray();
                $result[] = [
                    'key' => $row['key'],
                    'name' => $row['name'],
                    'short_name' => $row['short_name'],
                    'sub_title' => $row['sub_title'],
                    'venue' => $row['venue'],
                    'tournament' => $row['tournament'],
                    'format' => $row['format'],
                    'status' => $row['status'],
                    'team_a' => $team_a . " " . $team_a_score,
                    'team_b' => $team_b . " " . $team_b_score,
                    'start_at' => Carbon::parse($row['start_at']),
                    'start_at_local' => Carbon::parse($row['start_at_local']),
                    'message' => !empty($row['play']) ? !is_null($row['play']['result']) ? $row['play']['result']['msg'] : null : null ,
                    'piek'=> $piek,
                    'play' => $row['play'],
                    'teams' => $row['teams'],
                ];
            }
        }catch (\Exception $e){
            report($e);
        }
        return $result;
    }

    public function getTodayMatches(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $allMatches = $this->prepareTodayMatchesData($token);
        $result = [];
        foreach ($allMatches as $matches) {
            foreach ($matches as $match){
                $piek = [];
                if($match['players']){
                    foreach ($match['players'] as $playercode => $player){
                        $piek[$player['player']['name']] = [
                            'player_name' => $player['player']['name'],
                            'country' => $player['player']['nationality']['name'],
                            'score'  => !empty($player['score'][1]) ? !empty($player['score'][1]['batting']) ? $player['score'][1]['batting']['score'] : null : null,
                            'highest_score'  => !empty($player['score'][1]) ? !empty($player['score'][1]['batting']) ? $player['score'][1]['batting']['score']['runs'] : null : null,
                        ];
                    }
                }
                $piek = collect($piek)->sortBy('highest_score')->reverse()->toArray();
                $result[] = [
                    'key' => $match['key'],
                    'format' => $match['format'],
                    'teams' => $match['teams'],
                    'start_at' => $match['start_at'],
                    'venue' => $match['venue'],
                    'day_number' => $match['play']['day_number'],
                    'piek' => $piek
                ];
            }
        }
        return response()->success($result, "Today matches get succssfully");
    }

    public function prepareTodayMatchesData($token){
        $turnamentObj = new TournamentController();
        $allTournaments = $turnamentObj->getTournamentResponse($token);
        $todayDate = date('Y-m-d');
        $currentTournament = collect($allTournaments)->filter(function ($row) use ($todayDate){
            if(Carbon::parse($row['start_date'])->format('Y-m-d') <= $todayDate && Carbon::parse($row['last_scheduled_match_date'])->format('Y-m-d') > $todayDate){
                return $row;
            }
        });
        $allMatches = [];
        foreach ($currentTournament as $tournament) {
            $apiResult = sendRequest($token, 'tournament/'.$tournament["key"].'/featured-matches/');
            $allMatches[] = collect($apiResult['matches'])->filter(function ($row) use ($todayDate){
                if($row['status'] == 'started'){
                    return $row;
                }
            });
        }
        return $allMatches;
    }

    public function ongoingMatches(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $allMatches = $this->prepareOngoingData($token);
        $result = [];
        foreach ($allMatches as $matches){
            foreach ($matches as $match){
                $result[] = [
                    'key' => $match['key'],
                    'format' => $match['format'],
                    'teams' => $match['teams'],
                    'start_at' => $match['start_at'],
                    'venue' => $match['venue'],
                ];
            }
        }
        return response()->success($result, "Ongoing matches get succssfully");
    }

    public function prepareOngoingData($token) {
        $turnamentObj = new TournamentController();
        $allTournaments = $turnamentObj->getTournamentResponse($token);
        $todayDate = date('Y-m-d');
        $currentTournament = collect($allTournaments)->filter(function ($row) use ($todayDate){
            if(Carbon::parse($row['start_date'])->format('Y-m-d') <= $todayDate && Carbon::parse($row['last_scheduled_match_date'])->format('Y-m-d') > $todayDate){
                return $row;
            }
        });
        $allMatches = [];
        foreach ($currentTournament as $tournament) {
            $apiResult = sendRequest($token, 'tournament/'.$tournament["key"].'/featured-matches/');
            $allMatches[] = collect($apiResult['matches'])->filter(function ($row) use ($todayDate){
                if(Carbon::parse($row['start_at'])->format('Y-m-d') == $todayDate && $row['status'] != 'started'){
                    return $row;
                }
            });
        }
        return $allMatches;
    }

    public function upcomingMatches(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $allMatches = $this->prepareUpcomingData($token);
        $result = [];
        foreach ($allMatches as $matches){
            foreach ($matches as $match){
                $result[] = [
                    'key' => $match['key'],
                    'format' => $match['format'],
                    'teams' => $match['teams'],
                    'start_at' => $match['start_at'],
                    'venue' => $match['venue'],
                ];
            }
        }
        return response()->success($result, "Upcoming matches get succssfully");
    }

    public function prepareUpcomingData($token){
        $turnamentObj = new TournamentController();
        $allTournaments = $turnamentObj->getTournamentResponse($token);
        $todayDate = date('Y-m-d');
        $currentTournament = collect($allTournaments)->filter(function ($row) use ($todayDate){
            if(Carbon::parse($row['start_date'])->format('Y-m-d') <= $todayDate && Carbon::parse($row['last_scheduled_match_date'])->format('Y-m-d') > $todayDate){
                return $row;
            }
        });
        $allMatches = [];
        foreach ($currentTournament as $tournament) {
            $apiResult = sendRequest($token, 'tournament/'.$tournament["key"].'/featured-matches/');
            $allMatches[] = collect($apiResult['matches'])->filter(function ($row) use ($todayDate){
                if(Carbon::parse($row['start_at'])->format('Y-m-d') > $todayDate && $row['status'] == 'not_started'){
                    return $row;
                }
            });
        }
        return $allMatches;
    }

    public function getCompletedMatches(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $allMatches = $this->prepareCompletedMatchesData($token);
        $result = [];
        foreach ($allMatches as $matches){
            foreach ($matches as $match){
                $result[] = [
                    'key' => $match['key'],
                    'format' => $match['format'],
                    'teams' => $match['teams'],
                    'start_at' => $match['start_at'],
                    'venue' => $match['venue'],
                ];
            }
        }
        return response()->success($result, "Completed matches get succssfully");
    }

    public function prepareCompletedMatchesData($token){
        $turnamentObj = new TournamentController();
        $allTournaments = $turnamentObj->getTournamentResponse($token);
        $todayDate = date('Y-m-d');
        $currentTournament = collect($allTournaments)->filter(function ($row) use ($todayDate){
            if(Carbon::parse($row['start_date'])->format('Y-m-d') <= $todayDate && Carbon::parse($row['last_scheduled_match_date'])->format('Y-m-d') > $todayDate){
                return $row;
            }
        });
        $allMatches = [];
        foreach ($currentTournament as $tournament) {
            $apiResult = sendRequest($token, 'tournament/'.$tournament["key"].'/featured-matches/');
            $allMatches[] = collect($apiResult['matches'])->filter(function ($row) use ($todayDate){
                if($row['status'] == 'completed'){
                    return $row;
                }
            });
        }
        return $allMatches;
    }

    public function getMatchById($token, $request){
        $result = [];
        if(isset($request->match_key)) {
            $result = sendRequest($token, 'match/' . $request->match_key . '/');
        }
        return $result;
    }

    public function prepareStatsMatchDataByTournamentId($token, $request){
        $result = [];
        if(isset($request->tournament_key)) {
            $result = sendRequest($token, 'tournament/' . $request->match_key . '/stats/');
        }
        return $result;
    }

    public function getOverview(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();

        $result = [];
        $result['schedule'] = $this->prepareSheduleMatchesData($token);
        return response()->success($result, "Overview get succssfully");
    }

    //*********************************************************
    // Return Yesterday & todays's completed & Tomorrow Matches
    //*********************************************************
    public function prepareSheduleMatchesData($token){
        $turnamentObj = new TournamentController();
        $allTournaments = $turnamentObj->getTournamentResponse($token);
        $todayDate = date('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $currentTournament = collect($allTournaments)->filter(function ($row) use ($todayDate, $tomorrow){
            if(Carbon::parse($row['start_date'])->format('Y-m-d') <= $todayDate && Carbon::parse($row['last_scheduled_match_date'])->format('Y-m-d') > $tomorrow){
                return $row;
            }
        });
        $allMatches = [];
        foreach ($currentTournament as $tournament) {
            $apiResult = sendRequest($token, 'tournament/'.$tournament["key"].'/featured-matches/');
            $allMatches[] = collect($apiResult['matches'])->filter(function ($row) use ($todayDate, $yesterday, $tomorrow){
                $start_at = Carbon::parse($row['start_at'])->format('Y-m-d');
                if($start_at >= $yesterday && $start_at <= $tomorrow && $row['status'] != 'started'){
                    return $row;
                }
            });
        }

        return $allMatches;
    }
}
