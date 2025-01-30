<?php

return [

    /*
    * Enable Swagger
    */
    'enable' => env('SCRAMBLE_SWAGGER_ENABLED', true),

    /*
    * API URL
    */
    'url' => env('SCRAMBLE_SWAGGER_URL', 'docs/swagger'),

    /*
    * API Versions
    */
    'versions' => [
        'all',
        // "v1",
    ],

    'default_version' => 'all',

];
