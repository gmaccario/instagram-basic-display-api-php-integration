<?php 

namespace App;

use Lablnet\Encryption;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;

class IGBase
{
    // Protected because I need it in child classes, same for methods
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    public function getInstagramBasicDisplayByKeys()
    {
        return new InstagramBasicDisplay([
            'appId'         => $this->config['appId'],
            'appSecret'     => $this->config['appSecret'],
            'redirectUri'   => $this->config['redirectUri'],
        ]);
    }

    protected function getInstagramBasicDisplayByToken()
    {
        if(!\file_exists($this->config['token_file']))
        {
            die('Please, set up your long live token before anything else. Check out the README file.');
        }

        // Get long lived token from config json
        $tokenInfo = json_decode(file_get_contents($this->config['token_file']), true);

        // Decrypt the token
        $encryption = new Encryption($this->config['secretKey']);
        $decryptedToken = $encryption->decrypt($tokenInfo["access_token"]);	

        // Instantiate a new InstagramBasicDisplay object
        $instagram = new InstagramBasicDisplay($decryptedToken);

        // Set user access token
        $instagram->setAccessToken($decryptedToken);

        return $instagram;
    }
}
