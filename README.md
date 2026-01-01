
**Instagram Downloader + Cobalt API**
----------------


Project Overview
----------------
This project uses Cobalt API v11.5 to download media from multiple platforms, including Instagram. 
PHP serves as the backend to call the API and display downloaded media via a simple form.

Tech Stack
----------
- PHP 8.x
- GuzzleHTTP (composer require guzzlehttp/guzzle)
- Cobalt API v11.5 (running on http://localhost:9000)
- Laragon (development server)
- Composer (dependency management)

Project Structure
-----------------

project/
- app/
  - Controllers/
    - DownloadController.php
  - Services/
    - CobaltService.php
- config/
  - cobalt.php
- public/
  - index.php
- downloads/
- vendor/
- composer.json



Setup Instructions
------------------
1. Start the Cobalt API on port 9000:
   pnpm start

2. Make sure config/cobalt.php contains:
   return [
       'api_url' => 'http://localhost:9000',
       'timeout' => 10.0,
   ];

3. Install PHP dependencies:
   composer install
   composer require guzzlehttp/guzzle

4. Run the project via Laragon (no need for `php artisan serve`).

5. Access index.php via browser, input Instagram URL, and submit to download media.

Features Completed
------------------
- GET request to Cobalt API shows instance info (version, services)
- Form input for Instagram URL is connected to backend PHP
- PHP service (CobaltService) integrated with Cobalt API
- Displays media list (video/image) from Cobalt API response

Pending / Issues
----------------
- Video download is still failing; response returns "No media found"
- Cobalt API environment variables (COOKIE_PATH, API_KEY, API_AUTH_REQUIRED) need proper configuration to download all media
- Error handling and retry mechanism need improvement
- Multi-media support (Instagram Reels / IGTV) is pending

Notes
-----
- Make sure Cobalt API is running before testing the PHP project
- Testing can be done via browser or Postman
- For download functionality, Cobalt API requires valid cookie/session to access Instagram media
