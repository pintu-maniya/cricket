<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TokenGenerateController extends Controller
{
    public function getToken(){
        $client = new Client();
        return $client->post(env('CRICKET_API_BASEURL'),['api_key' => 'RS5:d9fd9a49cb26e9758952883bec6df1e6']);
    }
}
