<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data='',$message='') {
            $res = [
                'errors'  => false,
                'data' => $data,
                'message' => $message
            ];
            
            return Response::json($res);
        });

        Response::macro('error', function ($message, $status = 400,$data='') {
            $res = [
                'errors'  => true,
                'message' => $message,
                'data' => $data
            ];
           
            return Response::json($res, $status);
        });
    }
}
