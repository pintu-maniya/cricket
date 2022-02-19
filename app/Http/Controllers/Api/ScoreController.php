<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    protected $url = 'match/';

    public function getScoreboard(Request $request){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $result = $this->prepareScoreboardData($token, $request);
        return response()->success($result, "ScoreBoard get succssfully");
    }

    public function prepareScoreBoardData($token, $request) {
        $apiResult = sendRequest($token, $this->url.$request->match_key.'/');
        $result = [];
        try {
            $key = "";
            foreach($apiResult['teams'] as $team_key => $value) {
                if(in_array($request->team_key, $value)) {
                    $key = $team_key;
                }
            }

            $result['run'] = $apiResult['teams'][$key]['code'].' '.$apiResult['play']['innings'][$key.'_1']['score_str'];
            $result['toss_status'] = $apiResult['teams'][$apiResult['toss']['winner']]['code'] . ' Won toss & '.$apiResult['toss']['elected'];
            $result['player_count'] = count($apiResult['squad'][$key]['playing_xi']);
            $result['teams'] = $apiResult['teams'];
            $result['batting'] =  $this->getBattingData($apiResult, $key);
            $result['bawling'] =  $this->getBowlingData($apiResult, $key);

        }catch (\Exception $e){
            dd($e->getMessage());
            report($e);
        }
        return $result;
    }

    public function getBattingData($matchData, $teamKey){
        $result = [];
        foreach ($matchData['squad'][$teamKey]['playing_xi'] as $player_key => $row) {
            $result[$row] =  $matchData['players'][$row]['score']['1']['batting'];
        }
        return $result;
    }

    public function getBowlingData($matchData, $teamKey){
        $result = [];
        foreach ($matchData['squad'][$teamKey]['playing_xi'] as $player_key => $row) {
            $result[$row] =  $matchData['players'][$row]['score']['1']['bowling'];
        }
        return $result;
    }
}
