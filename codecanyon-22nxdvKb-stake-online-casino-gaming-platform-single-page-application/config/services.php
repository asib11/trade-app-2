<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   services.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

return [

    

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    
    'gtm' => [
        'container_id' => env('GTM_CONTAINER_ID'),
    ],

    
    'recaptcha' => [
        'public_key' => env('RECAPTCHA_PUBLIC_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'version' => env('RECAPTCHA_VERSION', 2),
        'min_score' => env('RECAPTCHA_MIN_SCORE', 0.5),
    ],

    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY', env('MAIL_PASSWORD')),
        'list_id' => env('SENDGRID_LIST_ID'),
    ],

    'blueassure' => [
        'user' => env('BLUEASSURE_USER'),
        'password' => env('BLUEASSURE_PASSWORD'),
    ],

    'postaffiliatepro' => [
        'script' => [
            'id' => env('POSTAFFILIATEPRO_SCRIPT_ID'),
            'url' => env('POSTAFFILIATEPRO_SCRIPT_URL'),
        ],
        'api' => [
            'url' => env('POSTAFFILIATEPRO_API_URL'),
        ],
        'signup_action_code' => env('POSTAFFILIATEPRO_SIGNUP_ACTION_CODE'),
        'cron' => [
            'time' => env('POSTAFFILIATEPRO_CRON_TIME', '02:00'),
        ]
    ],

    'api' => [
        'crypto' => [
            'provider' => env('API_CRYPTO_PROVIDER', 'coincap'),
            'providers' => [
                'coincap' => [
                    'endpoint' => env('API_CRYPTO_PROVIDERS_COINCAP_ENDPOINT', 'https://api.coincap.io/v2/'),
                    'api_key' => env('API_CRYPTO_PROVIDERS_COINCAP_API_KEY'),
                ],
                'cryptocompare' => [
                    'endpoint' => env('API_CRYPTO_PROVIDERS_CRYPTOCOMPARE_ENDPOINT', 'https://min-api.cryptocompare.com/data/'),
                    'api_key' => env('API_CRYPTO_PROVIDERS_CRYPTOCOMPARE_API_KEY'),
                ]
            ]
        ]
    ],
];
