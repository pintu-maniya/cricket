<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BallByBollController extends Controller
{
    public function getBollByBall(Request $request)
    {
        // previous_over_key
        $previous_over_key = $request->previous_over_key;
        $match_key = $request->match_key;
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $result = $this->prepareBallByBollData($token, $request);
        return response()->success($result, "BallByBall get succssfully");
    }

    public function prepareBallByBollData($token, $request){

        $result = [];
        try{
            if(isset($request->previous_over_key)){
                $apiResult = sendRequest($token, 'match/'.$request->match_key.'/ball-by-ball/'.$request->previous_over_key.'/');
            }else{
                $apiResult = sendRequest($token, 'match/'.$request->match_key.'/ball-by-ball/');
            }
        }catch(\Exception $e){
            dd($e->getMessage());
            report($e);
        }
        return $apiResult;
    }
}
