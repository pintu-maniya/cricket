<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;

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
                    'players' => $row['players'],
                ];
            }
        }catch (\Exception $e){
            report($e);
        }
        return $result;
    }
}
