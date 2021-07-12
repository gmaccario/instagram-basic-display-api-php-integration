<?php 

require __DIR__ . '/vendor/autoload.php';

use App\Utility;
use App\IGMedia;
use App\IGToken;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

$config = include 'config' . DIRECTORY_SEPARATOR . 'config.php';

if($config['debug'])
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);    
}

// Add log handler
$logger     = new Logger('ig-integration');
$handler    = new RotatingFileHandler('logs/ig-log.log', 0, Logger::INFO, true, 0664);
$handler->setFilenameFormat('{date}-{filename}', 'Y-m-d');
$logger->pushHandler($handler);

// Get long live token from query parameters
$longLivedToken = filter_input(INPUT_GET, 'long-lived-token', FILTER_SANITIZE_STRING);
$igAction       = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

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
        $logger->info('Success! Please check your new token in token.json');
    }
    else {
        $logger->info('Error! An error occurred saving the token');
    }
}
else {
    if($igAction == 'get-media')
    {
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
        $utility->filterMedia($media->data, 'media' . DIRECTORY_SEPARATOR);
    }
}

$logger->info('--------------------------------');
