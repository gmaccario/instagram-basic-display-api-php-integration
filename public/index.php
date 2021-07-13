<?php 

require __DIR__ . '/../vendor/autoload.php';

use App\Utility;
use App\IGMedia;
use App\IGToken;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

$config = include '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

if($config['debug'])
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);    
}

// Add log handler
$logger     = new Logger('ig-integration');
$handler    = new RotatingFileHandler('../logs/ig-log.log', 0, Logger::INFO, true, 0664);
$handler->setFilenameFormat('{date}-{filename}', 'Y-m-d');
$logger->pushHandler($handler);

// Get values from query parameters
$code           = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
$igAction       = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$longLivedToken = filter_input(INPUT_GET, 'long-lived-token', FILTER_SANITIZE_STRING);

$logger->info('--------------------------------');
$logger->info('New action: ' . $igAction);

if(isset($config) && $longLivedToken)
{
    // No config, no execution
    if(!$config['debug'])
    {
        $logger->info('Production environment. This action is not supported here.');
        return;
    }

    $logger->info('Start encrypt token');

    // Instagram Token class
    $iGToken = new IGToken($config, $longLivedToken);
    $result = $iGToken->encryptNewToken();

    if($result !== false)
    {
        $logger->info('Success! Please check your new token in ./config/token.json');
    }
    else {
        $logger->info('Error! An error occurred saving the token');
    }

    return;
}
else if($igAction == 'get-media') {

    $logger->info('Start IG get media');

    // Instagram Media class
    $iGMedia = new IGMedia($config, $longLivedToken);
    $media = $iGMedia->getMedia(); // stdClass->data

    $logger->info('Found ' . count($media->data) . ' media.');

    $iGToken = new IGToken($config);
    $result = $iGToken->refreshToken();

    $logger->info('Token ' . ($result ? 'refreshed' : 'not refreshed'));

    // App utilities
    $utility = new Utility();
    $result = $utility->filterMedia($media->data, 'media' . DIRECTORY_SEPARATOR);

    $logger->info('Filtered ' . count($result) . ' media.');

    return;
}
else {
    
    // Authenticate user (OAuth2)
    if(!empty($code))
    {
        // Ready for the login
        $iGToken = new IGToken($config);
        $instagram = $iGToken->getInstagramBasicDisplayByKeys();

        $logger->info('Code from Instagram: ' . $code);

        // Get the short lived access token (valid for 1 hour)
        $token = $instagram->getOAuthToken($code, true);

        // Exchange this token for a long lived token (valid for 60 days)
        $token = $instagram->getLongLivedToken($token, true);

        // Instagram Token class
        $iGToken->setLongLivedToken($token);

        $result = $iGToken->encryptNewToken();

        $logger->info('Encrypted new token successfully.');
        
        echo "<a href='?action=get-media'>Get media</a>";

        return;
    }
}

// Ready for the login and initialize the class
$iGToken = new IGToken($config);
$instagram = $iGToken->getInstagramBasicDisplayByKeys();

echo "<a href='{$instagram->getLoginUrl()}'>Instagram Login</a>";
