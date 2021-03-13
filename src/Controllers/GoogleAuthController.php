<?php

namespace MarcusGaius\ApiReports\Controllers;

use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MarcusGaius\ApiReports\Models\Report;

class GoogleAuthController extends Controller
{

    public function home(Request $request)
    {
        session_start();
        if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
            return redirect((new Client(require storage_path('client_config.php')))->createAuthUrl());
        };
        $report = Report::all();
        return view('apireports::home', compact('report'));
    }

    public function code()
    {
        session_start();
        $client = new Client(require storage_path('client_config.php'));
        $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
        return redirect(url('/'));
    }
    
}
