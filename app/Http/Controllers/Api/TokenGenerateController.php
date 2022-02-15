<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\ConnectionApi;
use Carbon\Carbon;

class TokenGenerateController extends Controller
{
    public function getToken(){

                $response = self::sendRequest();

                $insertUpdate = [
                    'token'         => $response['token'],
                    'expire_date'   => Carbon::parse($response['expires'])
                ];
                $connectonApi = new ConnectionApi();
                $result = $connectonApi->first();
                if($result){
                    $connectonApi->where('id',$result->id)->update($insertUpdate);
                }else{
                    $connectonApi->insert($insertUpdate);
                }
                return $response['token'];
    }

    public function sendRequest(){
        $client = new Client();
        //do{
            $res = $client->request('post',env('CRICKET_API_BASEURL') ,['form_params' => ['api_key' => env('CRICKET_API_KEY')]]);
            $data = json_decode($res->getBody()->getContents(),true);
        //}while(!empty($data));
        return $data['data'];
    }

    public function checkToken(){
        $connectionApi = new ConnectionApi();
        $checkTokenExpiredTime = $connectionApi->where('expire_date','>=',Carbon::now())->first();
        if(empty($checkTokenExpiredTime)){
            return $this->getToken();
        }else{
            return $checkTokenExpiredTime->token;
        }
    }

}
