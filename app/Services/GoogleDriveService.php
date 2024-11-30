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
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/client_id.json'));
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->setAccessType('offline');
        
        $this->driveService = new Drive($this->client);
    }

    public function uploadFile($filePath)
    {
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
