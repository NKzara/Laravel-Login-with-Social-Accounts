<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google_Client;
use Google_Service_Gmail;

class GoogleEmailController extends Controller
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
            $service = new Google_Service_Gmail($client);
            
            // Get latest 20 emails
            $optParams = [
                'maxResults' => 20,
                'q' => 'in:inbox',
            ];
            
            $results = $service->users_messages->listUsersMessages('me', $optParams);
            $messages = $results->getMessages();
            
            $emails = [];
            if ($messages) {
                foreach ($messages as $message) {
                    $messageDetail = $service->users_messages->get('me', $message->getId());
                    
                    $subject = '';
                    $from = '';
                    $date = '';
                    $snippet = $messageDetail->getSnippet();
                    
                    $headers = $messageDetail->getPayload()->getHeaders();
                    foreach ($headers as $header) {
                        if ($header->getName() === 'Subject') {
                            $subject = $header->getValue();
                        } elseif ($header->getName() === 'From') {
                            $from = $header->getValue();
                        } elseif ($header->getName() === 'Date') {
                            $date = $header->getValue();
                        }
                    }
                    
                    $emails[] = [
                        'id' => $message->getId(),
                        'subject' => $subject,
                        'from' => $from,
                        'date' => $date,
                        'snippet' => $snippet,
                        'unread' => in_array('UNREAD', $messageDetail->getLabelIds() ?? [])
                    ];
                }
            }

            return view('email', compact('emails'));

        } catch (\Exception $e) {
            return view('email')->with('error', 'Error fetching emails: ' . $e->getMessage());
        }
    }
}