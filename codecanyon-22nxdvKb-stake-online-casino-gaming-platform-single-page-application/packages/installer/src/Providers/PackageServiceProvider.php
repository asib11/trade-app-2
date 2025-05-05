<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   PackageServiceProvider.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace Packages\Installer\Providers;

use App\Providers\PackageServiceProvider as DefaultPackageServiceProvider;
use Illuminate\Support\Facades\Blade;

class PackageServiceProvider extends DefaultPackageServiceProvider
{
    protected $packageId = 'installer';

    
    public function boot()
    {
        
        $this->loadRoutesFrom($this->packageBasePath . '/routes/web.php');
        
        $this->loadViewsFrom($this->packageBasePath . '/resources/views', 'installer');
        
        Blade::componentNamespace('Packages\\Installer\\View\\Components', 'installer');
    }
}
