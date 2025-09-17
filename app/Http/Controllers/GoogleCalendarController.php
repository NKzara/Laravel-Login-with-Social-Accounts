<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarController extends Controller
{
    private function getGoogleClient()
    {
        $user = Auth::user();
        
        if (!$user || !$user->google_token) {
            return null;
        }

        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setAccessToken($user->google_token);

        // Check if token is expired and refresh if needed
        if ($client->isAccessTokenExpired()) {
            if ($user->google_refresh_token) {
                $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                
                // Update the stored token
                $newToken = $client->getAccessToken();
                $user->update(['google_token' => $newToken]);
            } else {
                return null;
            }
        }

        return $client;
    }

    public function index()
    {
        $client = $this->getGoogleClient();
        
        if (!$client) {
            return redirect()->route('google-auth')->with('error', 'Please authenticate with Google first.');
        }

        try {
            $service = new Google_Service_Calendar($client);
            
            // Get events from primary calendar
            $optParams = [
                'maxResults' => 20,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            ];
            
            $results = $service->events->listEvents('primary', $optParams);
            $events = $results->getItems();

            return view('calendar', compact('events'));

        } catch (\Exception $e) {
            return view('calendar')->with('error', 'Error fetching calendar events: ' . $e->getMessage());
        }
    }
}