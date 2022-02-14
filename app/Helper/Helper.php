<?php

use GuzzleHttp\Client;

function sendRequest($token,$endPoint, $string = null,$nextPage=null){
    try{
        $client = new Client();
        $res = $client->request('get', env('CRICKET_BASE_URL') . $endPoint,
            ['headers' =>
                ['rs-token' => $token]
            ]
        );
        $data = json_decode($res->getBody()->getContents(), true);
        if(!is_null($string)){
            return $data['data'][$string];
        }
        return $data['data'];
    }catch (Exception $e){
        \Illuminate\Support\Facades\Log::error($e);
    }

}
