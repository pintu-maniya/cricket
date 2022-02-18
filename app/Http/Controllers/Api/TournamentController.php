<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    private $url = 'featured-tournaments/';
    private $key = 'tournaments';

    public function getTournament()
    {
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $result = $this->getTournamentResponse($token);
        return response()->success($result, "Tournament get succssfully");
    }

    public function getTournamentResponse($token){
        $apiResult =sendRequest($token,$this->url,$this->key);
        $result = [];
        foreach ($apiResult as $row) {
            $result[] = [
                'key' => $row['key'],
                'short_name' => $row['short_name'],
                'title' => $row['name'],
                'start_date' => Carbon::parse($row['start_date']),
                'association_key' => $row['association_key'],
                'is_date_confirmed' => $row['is_date_confirmed'],
                'is_venue_confirmed' => $row['is_venue_confirmed'],
                'last_scheduled_match_date' => Carbon::parse($row['last_scheduled_match_date']),
            ];
        }
        return $result;
    }
}
