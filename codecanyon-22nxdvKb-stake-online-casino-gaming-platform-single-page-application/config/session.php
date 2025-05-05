<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   session.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

use Illuminate\Support\Str;

return [

    

    'driver' => env('SESSION_DRIVER', 'file'),

    

    'lifetime' => env('SESSION_LIFETIME', 10080), 

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', FALSE),

    

    'encrypt' => env('SESSION_ENCRYPT', FALSE),

    

    'files' => storage_path('framework/sessions'),

    

    'connection' => env('SESSION_CONNECTION'),

    

    'table' => env('SESSION_TABLE', 'sessions'),

    

    'store' => env('SESSION_STORE'),

    

    'lottery' => [2, 100],

    

    'cookie' => env('SESSION_COOKIE', 'stake_session'),

    

    'path' => env('SESSION_PATH', '/'),

    

    'domain' => env('SESSION_DOMAIN'),

    

    'secure' => env('SESSION_SECURE_COOKIE'),

    

    'http_only' => env('SESSION_HTTP_ONLY', FALSE),

    

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
