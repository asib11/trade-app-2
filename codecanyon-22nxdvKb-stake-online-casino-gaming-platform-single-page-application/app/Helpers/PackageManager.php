<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   PackageManager.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;


class PackageManager
{
    protected Collection $packages;

    function __construct()
    {
        $this->packages = collect();

        foreach((new Finder())->in(base_path('packages'))->name('config.json')->files() as $configFile) {
            $this->load($configFile->getPathname());
        }

        return $this;
    }

    
    public function load(string $configFile): PackageManager
    {
        if ($package = json_decode(file_get_contents($configFile))) {
            $package->code = env($this->getCodeVariable($package->id));
            $package->code_required = in_array($package->type, ['game', 'add-on', 'prediction', 'provider']) && $package->id != 'baccarat';
            $package->model = sprintf('%sModels\\%s', $package->namespace, Str::studly($package->id));
            $package->min_app_version_installed = version_compare(config('app.version'), $package->min_app_version, '>=');
            $package->installed = file_exists(base_path('packages/' . $package->base_path . '/' . $package->source_path . '/' . str_replace([$package->namespace, '\\'], ['','/'], $package->providers[0]) . '.php'));
            $package->enabled = $package->installed &&
                isset($package->min_app_version) &&
                version_compare(config('app.version'), $package->min_app_version, '>=') &&
                !Storage::disk('local')->exists(sprintf('packages/%s/disabled', $package->id)) &&
                (env($this->getCodeVariable($package->id)) || !$package->code_required);

            $this->packages->put($package->id, $package);
        }

        return $this;
    }

    
    public function initAttributes(): PackageManager
    {
        foreach ($this->getEnabled() as $package) {
            $package->version = config($package->id . '.version');
            $package->name = __($package->name);
        }

        return $this;
    }

    
    public function get(string $id, string $key = 'id'): ?object
    {
        return $this->packages?->firstWhere($key, $id);
    }

    
    public function getPackageProperty(string $id, string $property): mixed
    {
        return $this->get($id)->{$property} ?? NULL;
    }

    
    public function getAll(): array
    {
        return $this->packages->sortBy('name')->all();
    }


    
    public function getInstalled(): Collection
    {
        return $this->packages->where('installed', TRUE);
    }

    
    public function getEnabled(): Collection
    {
        return $this->packages->where('enabled', TRUE);
    }

    
    public function getPackageIdByClass(string $class): string
    {
        
        return preg_replace('#([0-9]+)-#', '-$1', Str::kebab(class_basename($class)));
    }

    public function getCodeVariable(string $id): string
    {
        return str($id)->upper()->replace('-', '_')->append('_')->append(constant("\x46\x50\x5f\x43\x4f\x44\x45"));
    }

    public function getHashVariable($id): string
    {
        return str($id)->upper()->replace('-', '_')->append('_')->append(constant("\x46\x50\x5f\x48\x41\x53\x48"));
    }

    
    public function autoload(string $className): void
    {
        foreach ($this->getInstalled() as $package) {
            
            $static = (array) $package->static;

            
            if (in_array($className, array_keys($static))) {
                include_once base_path('packages/' . $package->base_path . '/' . $static[$className] . '/' . $className . '.php');
            
            } elseif (strpos($className, $package->namespace) !== FALSE) {
                include_once base_path('packages/' . $package->base_path . '/' . $package->source_path . '/' . str_replace([$package->namespace, '\\'], ['','/'], $className) . '.php');
            }
        }
    }
}
