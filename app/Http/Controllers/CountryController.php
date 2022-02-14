<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $url = 'country/list/';
    protected $key = 'countries';

    public function getCountryList(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $countries = $this->getCountry($token,$this->url);

        if ($countries) {
            return response()->success($countries);
        }
        return response()->error('Sorry, no Association list found');
    }
    public function getCountry($token, $url,$result = []){
        $apiResult = sendRequest($token, $url);
        $result = array_merge($result,$apiResult[$this->key]);
        if(!empty($apiResult['next_page_key'])){
            return $this->getCountry($token,$url.$apiResult['next_page_key']."/",$result);
        }
        return $result;
    }
}
