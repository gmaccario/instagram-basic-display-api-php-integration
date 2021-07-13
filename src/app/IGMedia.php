<?php 

namespace App;

class IGMedia extends IGBase
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function getMedia()
    {
        $instagram = $this->getInstagramBasicDisplayByToken();

        return $instagram->getUserMedia('me', $this->config['limit_per_page']);
    }
}
