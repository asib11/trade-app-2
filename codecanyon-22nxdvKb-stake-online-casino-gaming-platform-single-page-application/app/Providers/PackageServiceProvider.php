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

namespace App\Providers;

use App\Helpers\PackageManager;
use Illuminate\Support\ServiceProvider;
use Exception;
use Illuminate\Support\Str;

class PackageServiceProvider extends ServiceProvider
{
    protected $packageId;

    protected $packageBasePath;

    protected $id;

    public function __construct()
    {
        parent::__construct(app());

        if (!$this->packageId) {
            throw new Exception('Package ID must be set in the child PackageServiceProvider class.');
        }

        $this->packageBasePath = base_path('packages/' . $this->packageId);
        $this->id = (string) Str::of($this->packageId)->append("\x2e\x6c\x69\x76\x65");
    }

    
    public function register()
    {
        
        $this->mergeConfigFrom($this->packageBasePath . '/config/config.php', $this->packageId);
    }

    
    public function boot()
    {
        
        $this->loadMigrationsFrom($this->packageBasePath . '/database/migrations');
        
        $this->loadRoutesFrom($this->packageBasePath . '/routes/api.php');

        config([$this->id => TRUE]);
    }
}
