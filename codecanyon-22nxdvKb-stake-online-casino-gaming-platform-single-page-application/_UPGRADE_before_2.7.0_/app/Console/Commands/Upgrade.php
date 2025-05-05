<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   Upgrade.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace App\Console\Commands;

use App\Facades\Manager;
use App\Helpers\PackageManager;
use App\Services\ArtisanService;
use Illuminate\Console\Command;

class Upgrade extends Command
{
    
    protected $signature = 'app:upgrade {id?}';

    
    protected $description = 'Upgrade the application to the latest version';

    
    public function handle(PackageManager $packageManager)
    {
        set_time_limit(900);

        $id = $this->argument('id');

        if (!$id || $id === 'app') {
            dump('Upgrading application');
            Manager::download(env(FP_CODE), env(FP_EMAIL));
        }

        $packageManager->getEnabled()
            ->filter(fn($package) => isset($package->hash) && (!$id || $package->id === $id))
            ->each(function($package) {
                dump(sprintf('Upgrading %s add-on', $package->id));
                Manager::download($package->code, env(FP_EMAIL), $package->hash, config(sprintf('%s.version', $package->id)));
            });

        ArtisanService::migrateAndSeed();
        ArtisanService::clearAllCaches();
    }
}
