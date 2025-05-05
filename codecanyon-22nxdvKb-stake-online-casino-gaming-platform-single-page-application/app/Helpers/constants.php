<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   constants.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

define('HTTP_CODE_EMAIL_NOT_VERIFIED', 455);
define('HTTP_CODE_2FA_NOT_PASSED', 456);
define('HTTP_CODE_KYC_NOT_PASSED', 457);

define('FP_CODE', hex2bin('50555243484153455f434f4445'));
define('FP_EMAIL', hex2bin('4c4943454e5345455f454d41494c'));
define('FP_HASH', hex2bin('53454355524954595f48415348'));

define('STAKE_QUEUE_PROVIDER_GAMES', 'provider-games');
define('STAKE_QUEUE_MULTIPLAYER_GAMES', 'multiplayer-games');
