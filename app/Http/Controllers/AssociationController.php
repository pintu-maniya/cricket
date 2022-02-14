<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssociationController extends Controller
{
    protected $url = 'association/list/';
    protected $key = 'associations';
    protected $associationCountry = 'association/list-by-country/';

    public function getAssociationList(){
        $tokenObj = new TokenGenerateController();
        $country = new CountryController();
        $token = $tokenObj->checkToken();
        $countries = $country->getCountry($token,'country/list/');
        $associcationResult = $this->getAssocicationListByPage($token,$this->url);
        $getCountryAssocication = $this->getCountryAssocication($token,$associcationResult,$countries);

        dd($getCountryAssocication);
        $result = [];
        if ($apiResult) {
            dd($apiResult);
            foreach ($apiResult as $asociation) {
                try {
                    dd($asociation);
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
            return response()->success($result);
        }
        return response()->error('Sorry, no Association list found');
    }

    public function getAssocicationListByPage($token, $url, $result=[]){

        $apiResult = sendRequest($token, $url);
        $result = array_merge($result,$apiResult[$this->key]);
        if(!empty($apiResult['next_page_key'])){
            return $this->getAssociationList($token,$url.$apiResult['next_page_key'],$apiResult,$result);
        }
        return $result;
    }
    public function getCountryAssocication($token,$associationResult,$countries){
        $newResult = [];
        foreach ($associationResult as $key => &$association){
                foreach ($countries as $country){
                    $association['association_country'][] = sendRequest($token, $this->associationCountry.$country['code']);
                }
            dd($association);
        }
        dd($associationResult);
//        $result = array_merge($result,$apiResult[$this->key]);
        /*if(!empty($apiResult['next_page_key'])){
            return $this->getAssociationList($token,$url."/".$apiResult['next_page_key'],$apiResult,$result);
        }*/
        return $result;
    }

}
