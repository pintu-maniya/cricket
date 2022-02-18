<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $url = 'country/list/';
    protected $key = 'countries';

    public function getCountryList(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $countries = $this->getCountry($token);

        if ($countries) {
            return response()->success($countries);
        }
        return response()->error('Sorry, no Association list found');
    }
    public function getCountry($token, $result = []){

        $apiResult = sendRequest($token, $this->url);
        if($apiResult){
            $result = array_merge($result,$apiResult[$this->key]);
            if(!empty($apiResult['next_page_key'])){
                return $this->getCountry($token,$url.$apiResult['next_page_key']."/",$result);
            }
            return $result;
        }
        return;
    }

    public function country(){
        $response = Country::all();
        return response()->json($response);
    }
}
