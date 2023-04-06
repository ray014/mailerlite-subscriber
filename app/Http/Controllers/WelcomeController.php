<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helper\SubscriberHelper;

class WelcomeController extends Controller
{
    /**
     * Show welcome page
     */
    public function index(Request $request)
    {
        $apiKeyExist = SubscriberHelper::getApiKeyFromDb() ? true : false;

        return view('welcome', ["apiKeyExist" => $apiKeyExist]);
    }
}