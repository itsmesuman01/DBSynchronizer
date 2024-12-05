<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
    private $client;
    private $driveService;

    public function __construct()
    {
        // Initialize Google Client
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/client_id.json'));
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->setAccessType('offline');
        
        $this->driveService = new Drive($this->client);
    }

    public function authenticate()
    {
        // Check if there is a valid token stored
        if ($this->client->isAccessTokenExpired()) {
            // Token is expired or not available, need to authenticate
            $accessToken = $this->getAccessTokenFromStorage();

            if ($accessToken) {
                // If a token exists, we set it in the client
                $this->client->setAccessToken($accessToken);
            } else {
                // Start OAuth flow if no token
                $authUrl = $this->client->createAuthUrl();
                echo "Visit the following URL to authorize the application: $authUrl\n";

                // Get the code from the user
                $code = readline('Enter the authorization code: ');

                // Authenticate and get access token
                $this->client->authenticate($code);
                $accessToken = $this->client->getAccessToken();

                // Store the access token
                $this->storeAccessToken($accessToken);
            }
        }
    }

    private function getAccessTokenFromStorage()
    {
        $tokenPath = storage_path('app/google-access-token.json');

        if (file_exists($tokenPath)) {
            return json_decode(file_get_contents($tokenPath), true);
        }

        return null;
    }

    private function storeAccessToken($accessToken)
    {
        file_put_contents(storage_path('app/google-access-token.json'), json_encode($accessToken));
    }

    public function uploadFile($filePath)
    {
          // Authentication and uploading
        $this->authenticate();
        
        $fileName = basename($filePath);

        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => ['root'] // PATH TO STORE THE FILE IN GOOGLE DRIVE
        ]);
        
        $content = file_get_contents($filePath);
        
        $file = $this->driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => 'application/sql',
            'uploadType' => 'multipart'
        ]);

        if ($file) {
            echo "File uploaded successfully: " . $file->name;
        } else {
            echo "File upload failed.";
        }
    }
}
