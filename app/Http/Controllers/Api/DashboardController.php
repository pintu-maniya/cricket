<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $client = new Client();

    }
}
