<?php 

return array(
    'secretKey'     => '', // used to encrypt the token, you must create your custom (random) string
    'appId'         => '',
    'appSecret'     => '',
    'redirectUri'   => '',

    'limit_per_page'=> 8,

    'exclude'       => array(
        // list of names of IG media to exclude, e.g. '1262629994_225747615234351_9142654999463375560_n.jpg',
    ),

    'debug'         => true,
    'token_file'    => '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'token.json',
);
