<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   Manager.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Manager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'manager';
    }
}
