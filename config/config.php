<?php 

return array(
    'secretKey'     => '7h3S3cr37H1s70ry0f1GIn73gra710n', // used to encrypt the token

    'limit_per_page'=> 8,

    'exclude'       => array(
        // list of names of IG media to exclude, e.g. '1262629994_225747615234351_9142654999463375560_n.jpg',
    ),

    'debug'         => true,
    'token_file'    =>'config' . DIRECTORY_SEPARATOR . 'token.json',
);
