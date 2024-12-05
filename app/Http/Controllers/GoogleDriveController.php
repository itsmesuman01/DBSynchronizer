<?php

namespace App\Http\Controllers;

use Google\Client;
use Google_Service_Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoogleDriveController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/client_id.json'));
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        
        // Generate the URL to the google login page
        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/client_id.json'));
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        
        // Get the code from the request
        $code = $request->get('code');
        
        // Exchange the authorization code for an access token
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        
        if (isset($accessToken['error'])) {
            return redirect()->route('google.auth')->with('error', 'Error during Google authentication');
        }

        Session::put('google_access_token', $accessToken);

        return redirect()->route('home')->with('message', 'Google authentication successful');
    }
}
