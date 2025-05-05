<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   web.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

use Illuminate\Support\Facades\Route;
use Packages\Installer\Http\Controllers\InstallerController;
use Packages\Installer\Http\Middleware\RedirectIfInstalled;


Route::prefix('install')
    ->name('install.')
    ->middleware(['web', RedirectIfInstalled::class])
    ->group(function () {
        
        Route::get('{step}', [InstallerController::class, 'view'])->where('step', '(1|2|3|4)')->name('view');
        
        Route::post('{step}/process', [InstallerController::class, 'process'])->where('step', '(1|2|3)')->name('process');
    });
