<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DBbackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the MySQL database and upload it to Google Drive';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $filename = storage_path('app/backups/' . $database . '-' . now()->format('Y-m-d-H-i-s') . '.sql');

        // Perform mysqldump
        $command = "mysqldump -u $username -p$password -h $host $database > $filename";

        // Execute the mysqldump command
        $output = null;
        $resultCode = null;

        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            $this->info('Backup successfully created: ' . $filename);
        } else {
            $this->error('Backup failed');
            return;
        }

        // Call the method to upload the backup to Google Drive
        $this->uploadToGoogleDrive($filename);
    }

    private function uploadToGoogleDrive($filePath)
    {
        $googleDriveService = new \App\Services\GoogleDriveService();
        
        // Authenticate before uploading
        $googleDriveService->authenticate();

        // Upload the file
        $googleDriveService->uploadFile($filePath);
    }
}
