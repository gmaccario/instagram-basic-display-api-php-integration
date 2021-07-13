<?php 

namespace App;

use Carbon\Carbon;
use Lablnet\Encryption;

class IGToken extends IGBase
{
    private $longLivedToken;

    public function __construct(array $config, string $longLivedToken = '')
    {
        parent::__construct($config);

        $this->longLivedToken = $longLivedToken;
    }

    public function getLongLivedToken()
    {
        return $this->longLivedToken;
    }

    public function setLongLivedToken(string $token)
    {
        return $this->longLivedToken = $token;
    }

    public function encryptNewToken()
    {
        if(!$this->longLivedToken)
        {
            die('Please, send the right value for the long-lived-token parameter.');
        }

        // Encrypt the token
        $encryption = new Encryption($this->config['secretKey']);
        $cryptedToken = $encryption->encrypt($this->longLivedToken);
        
        // Save on file for later
        return file_put_contents($this->config['token_file'], json_encode(
            array(
                "access_token" => $cryptedToken,
                "token_type" => 'init',
                "expires_in" => Carbon::now()->addMonths(2)->timestamp,
            )
        ));
    }

    public function refreshToken()
    {
        // Get long lived token from config json
        $tokenInfo = json_decode(file_get_contents($this->config['token_file']), true);

        // Decrypt the token
        $encryption = new Encryption($this->config['secretKey']);
        $decryptedToken = $encryption->decrypt($tokenInfo["access_token"]);	

        // Get long lived token from config json
        $tokenInfo = json_decode(file_get_contents($this->config['token_file']), true);

        // Check the expiration date
        $expiresInFinalTimestamp = $tokenInfo['expires_in'] + Carbon::now()->timestamp;

        // One week before the expiration
        $oneWeekBeforeExpirationTimestamp = ($tokenInfo['expires_in'] + Carbon::now()->timestamp) - 604800;

        // IG Token usually expires after 2 months.
        // Refresh the token only if the now is greater than one week before expiration. 
        // Note for thest purpose: Carbon::now()->addMonths(3)->timestamp INSTEAD OF Carbon::now()->timestamp
        if(Carbon::now()->timestamp > $oneWeekBeforeExpirationTimestamp)
        {
            $instagram = $this->getInstagramBasicDisplayByToken();
            $oAuthTokenAndExpiresDate = $instagram->refreshToken($decryptedToken);

            // $oAuthTokenAndExpiresDate->access_token;
            // $oAuthTokenAndExpiresDate->token_type;
            // $oAuthTokenAndExpiresDate->expires_in;

            if(isset($oAuthTokenAndExpiresDate->access_token))
            {
                // Encrypt the token
                $encryption = new Encryption($this->config['secretKey']);
                $cryptedToken = $encryption->encrypt($oAuthTokenAndExpiresDate->access_token);

                // Save on file for later
                file_put_contents($this->config['token_file'], json_encode(
                    array(
                        "access_token" => $cryptedToken,
                        "token_type" => $oAuthTokenAndExpiresDate->token_type,
                        "expires_in" => $oAuthTokenAndExpiresDate->expires_in,
                    )
                ));

                return true;
            }
        }

        return false;
    }
}
