<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google_Client;
use Google_Service_Tasks;

class GoogleTasksController extends Controller
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
            $service = new Google_Service_Tasks($client);
            
            // Get task lists first
            $taskLists = $service->tasklists->listTasklists();
            $allTasks = [];
            
            foreach ($taskLists->getItems() as $taskList) {
                $tasks = $service->tasks->listTasks($taskList->getId(), [
                    'maxResults' => 50,
                    'showCompleted' => true,
                    'showDeleted' => false,
                    'showHidden' => true,
                ]);
                
                foreach ($tasks->getItems() as $task) {
                    $allTasks[] = [
                        'id' => $task->getId(),
                        'title' => $task->getTitle(),
                        'notes' => $task->getNotes(),
                        'status' => $task->getStatus(),
                        'due' => $task->getDue(),
                        'updated' => $task->getUpdated(),
                        'completed' => $task->getCompleted(),
                        'taskListTitle' => $taskList->getTitle(),
                        'taskListId' => $taskList->getId(),
                    ];
                }
            }
            
            // Sort by updated date (most recent first)
            usort($allTasks, function($a, $b) {
                return strtotime($b['updated']) - strtotime($a['updated']);
            });

            return view('todos', ['tasks' => $allTasks]);

        } catch (\Exception $e) {
            return view('todos')->with('error', 'Error fetching tasks: ' . $e->getMessage());
        }
    }
}