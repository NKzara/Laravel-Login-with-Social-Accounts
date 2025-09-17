<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        // Build Google OAuth URL manually with all required scopes
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect');
        $state = Str::random(40);
        
        // Store state in session for security
        session(['google_oauth_state' => $state]);
        
        $scopes = implode(' ', [
            'openid',
            'email',
            'profile',
            'https://www.googleapis.com/auth/calendar.readonly',
            'https://www.googleapis.com/auth/gmail.readonly',
            'https://www.googleapis.com/auth/tasks.readonly'
        ]);
        
        $params = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => $scopes,
            'response_type' => 'code',
            'state' => $state,
            'access_type' => 'offline',
            'approval_prompt' => 'force',
        ]);
        
        $authUrl = 'https://accounts.google.com/o/oauth2/auth?' . $params;
        
        return redirect($authUrl);
    }

    public function callbackGoogle(Request $request)
    {
        try {
            // Verify state parameter for security
            $sessionState = session('google_oauth_state');
            $requestState = $request->get('state');
            
            if (!$sessionState || $sessionState !== $requestState) {
                return redirect()->route('login')->with('error', 'Invalid OAuth state parameter.');
            }
            
            // Clear the state from session
            session()->forget('google_oauth_state');
            
            // Get the authorization code
            $code = $request->get('code');
            if (!$code) {
                return redirect()->route('login')->with('error', 'Authorization code not received.');
            }
            
            // Exchange code for access token
            $tokenResponse = $this->getAccessToken($code);
            
            if (!$tokenResponse || !isset($tokenResponse['access_token'])) {
                return redirect()->route('login')->with('error', 'Failed to obtain access token.');
            }
            
            // Get user info from Google
            $userInfo = $this->getUserInfo($tokenResponse['access_token']);
            
            if (!$userInfo) {
                return redirect()->route('login')->with('error', 'Failed to get user information.');
            }
            
            // Find or create user
            $user = User::where('google_id', $userInfo['id'])->first();
            
            // Prepare token data
            $tokenData = [
                'access_token' => $tokenResponse['access_token'],
                'refresh_token' => $tokenResponse['refresh_token'] ?? null,
                'expires_in' => $tokenResponse['expires_in'] ?? 3600,
                'created' => now()->timestamp,
            ];
            
            if (!$user) {
                $new_user = User::create([
                    'name' => $userInfo['name'] ?? $userInfo['email'],
                    'email' => $userInfo['email'],
                    'google_id' => $userInfo['id'],
                    'google_token' => $tokenData,
                    'google_refresh_token' => $tokenResponse['refresh_token'] ?? null,
                ]);
                
                Auth::login($new_user);
                return redirect()->intended('dashboard')->with('success', 'Successfully logged in with Google!');
            } else {
                // Update existing user's token
                $user->update([
                    'google_token' => $tokenData,
                    'google_refresh_token' => $tokenResponse['refresh_token'] ?? $user->google_refresh_token,
                ]);
                
                Auth::login($user);
                return redirect()->intended('dashboard')->with('success', 'Successfully logged in with Google!');
            }
            
        } catch (\Throwable $th) {
            return redirect()->route('login')->with('error', 'Error during Google authentication: ' . $th->getMessage());
        }
    }
    
    /**
     * Exchange authorization code for access token
     */
    private function getAccessToken($code)
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'client_id' => config('services.google.client_id'),
                    'client_secret' => config('services.google.client_secret'),
                    'redirect_uri' => config('services.google.redirect'),
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                ]
            ]);
            
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('Google token exchange failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get user information from Google
     */
    private function getUserInfo($accessToken)
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ]
            ]);
            
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('Google user info failed: ' . $e->getMessage());
            return null;
        }
    }
}