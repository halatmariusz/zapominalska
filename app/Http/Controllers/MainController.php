<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function receive(Request $request)
    {
        $access_token = "EAAG8cBI8IOwBAGj9iJUpvEyteR1girWpWoUJXhHVQQJ0iuXAZCVbslvJsYYeoiajqi5ZCDGqcu2HJFP8WrTZAUsit3WhKS7UO3nZC4Rgr9E0ZC7OCMgl0ZASIuUUra85h0iA5VSl3ZCUt3fmntaQO2BwW7LE5XY2Iw5sp7a4tZAQZAwZDZD";
        $verify_token = "Mariusz";
        $hub_verify_token = null;
        
        if(isset($_REQUEST['hub_challenge'])) {
            $challenge = $_REQUEST['hub_challenge'];
            $hub_verify_token = $_REQUEST['hub_verify_token'];
        }
        
        
        if ($hub_verify_token === $verify_token) {
            echo $challenge;
        }
    }
}
