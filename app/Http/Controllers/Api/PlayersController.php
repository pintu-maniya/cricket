<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    public function getPlayer(Request $request)
    {
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
//        $obj = new \stdClass();
//        $obj->match_id = 'auswengw_2022_one-day_03';
//        $obj->player_id = 'w_gra_harris';
        $result = $this->getPlayerByPlayerId($token, $request);
        return response()->success($result);
    }

    public function getPlayerByPlayerId($token, $obj){
        $result = [];
        if(isset($obj->match_id)){
            $apiResult = sendRequest($token, 'match/'.$obj->match_id.'/');
            $result = $apiResult['players'][$obj->player_id];
        }
        return $result;
    }
}
