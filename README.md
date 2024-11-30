# MySQL Backup Synchronizer for XAMPP to Google Drive

This Laravel application automatically backs up your XAMPP MySQL database and syncs the backup to Google Drive for secure storage.

## Requirements

- PHP >= 7.3
- Laravel 8.x or higher
- MySQL (via XAMPP)
- Google Cloud Project for Google Drive API
- Composer
- Google OAuth 2.0 credentials (client ID and secret)

## Installation

1. **Clone the repository:**
    git clone https://github.com/itsmesuman01/DBSynchronizer.git
    cd DBSynchronizer

2. **Install dependencies:**
    composer install

3. **Copy the environment file:**
    cp .env.example .env
    php artisan key:generate

4. **Set up Google API credentials:**
    - Go to [Google Cloud Console](https://console.developers.google.com/).
    - Create a new project.
    - Enable the **Google Drive API**.
    - Create OAuth 2.0 credentials and download the `credentials.json` file.
    - Place `credentials.json` in the root of your Laravel project.

5. **Update your `.env` file** with your MySQL and Google Drive credentials:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_mysql_username
    DB_PASSWORD=your_mysql_password
    GOOGLE_CLIENT_ID=your_google_client_id
    GOOGLE_CLIENT_SECRET=your_google_client_secret
    GOOGLE_REDIRECT_URI=http://yourdomain.com/google/callback

6. **Create symbolic link for storage:**
    php artisan storage:link

7. **Authenticate with Google:**
    Run the following command to authenticate with Google:
    php artisan google:auth
    This will redirect you to a browser to allow the app to access your Google Drive.

### Manual Backup
To manually back up your MySQL database and upload it to Google Drive, run:
php artisan db:backup
