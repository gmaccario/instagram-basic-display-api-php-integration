<?php 

namespace App;

use Lablnet\Encryption;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;

class IGMedia extends IGBase
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function getMedia()
    {
        $instagram = $this->getInstagramBasicDisplay();

        return $instagram->getUserMedia('me', $this->config['limit_per_page']);
    }
}