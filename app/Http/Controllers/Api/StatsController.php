<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\TokenGenerateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatsController extends Controller
{
    protected $url = 'tournament/auswengw_2022/stats/';

    public function getStats()
    {
        $tokenObj = new TokenGenerateController();
        $TournamentController = new TournamentController();
        $token = $tokenObj->checkToken();
        $result = $this->preparedStateData($token);
        $tournamentList = $TournamentController->getTournamentResponse($token);
        if(!empty($tournamentList)){
            $apiResult = sendRequest($token, $this->url);
            return response()->success($apiResult, "Stats get succssfully");
        }

        return response()->error('Sorry, no tournament found');
    }

    public function preparedStateData($token)
    {
        $TournamentController = new TournamentController();
        $tournamentList = $TournamentController->getTournamentResponse($token);
        $apiResult = sendRequest($token, $this->url);
        $result = [];
        if ($tournamentList) {
            foreach ($tournamentList as $tournament) {
                try {
                    $apiResult = sendRequest($token, 'tournament/' . $tournament["key"] . '/stats/');
                    if ($apiResult) {
                        $array = array_map('array_filter', $apiResult['player']['batting']);
                        $array = array_filter($array);
                        if ($array) {
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
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
        }

        return $result;
    }
}
