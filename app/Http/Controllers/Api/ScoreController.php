<?php

namespace App\Http\Controllers\Api;

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
            foreach ($apiResult['squad'][$key]['player_keys'] as $player_key => $row) {
                $result[$row] =  $apiResult['players'][$row]['score']['1']['batting'];
            }
        }catch (\Exception $e){
            dd($e->getMessage());
            report($e);
        }
        return $result;
    }
}
