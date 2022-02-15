<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function getTeams()
    {
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $tournamentList = TournamentController::getTournamentResponse($token);
        $apiResult = sendRequest($token, 'team//');
        $result = [];
        if ($tournamentList) {
            foreach ($tournamentList as $tournament) {
                try {
                    $apiResult = sendRequest($token, 'tournament/' . $tournament["key"] . '/stats/');
                    if ($apiResult) {
                        $array = array_map('array_filter', $apiResult['player']['batting']);
                        $array = array_filter($array);
                        if ($array) {
                            $result = $this->preparedStateData($apiResult);
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
            return response()->success($result);
        }
        return response()->error('Sorry, no tournament found');
    }

    public function preparedStateData($apiResult)
    {
        $result = [];
        $apiResult = json_decode(json_encode($apiResult),true);
        try {
            foreach ($apiResult as $player => $playerData) {
                if($playerData){
                    foreach ($playerData as $battingBowlingFielding => $battingBowlingFieldingData) {
                        if(in_array($battingBowlingFielding,['batting','bowling','feilding'])){
                            foreach ($battingBowlingFieldingData as $mostData => $data) {
                                $currentArray = (count($data) > 0) ? current($data) : "";
                                foreach($data as $key => $row){
                                    $result[$mostData][] = [
                                        'run' => $row['value'] ?? NULL,
                                        'player_name' => $apiResult['players'][$row['player_key']] ?? "",
                                        'country' => $apiResult['players'][$row['player_key']]['nationality'] ?? "",
                                    ];
                                }
                            }
                        }

                    }
                }

            }
        } catch (\Exception $e) {
            report($e);
        }
        return $result;
    }
}
