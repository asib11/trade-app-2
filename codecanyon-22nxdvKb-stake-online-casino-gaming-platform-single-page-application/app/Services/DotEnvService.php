<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   DotEnvService.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace App\Services;

use Dotenv\Dotenv;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DotEnvService
{
    const ENV = '.env';
    const ENV_INSTALL = '.env.install';

    private $env = [];

    public function __construct()
    {
        return $this->exists() ? $this->load() : $this->createAndLoad();
    }

    protected function exists(): bool
    {
        return File::exists($this->getEnvFilePath());
    }

    protected function load(): DotEnvService
    {
        $this->env = Dotenv::createArrayBacked(base_path())->safeLoad();

        return $this;
    }

    
    protected function createAndLoad(): DotEnvService
    {
        $baseEnvFilePath = base_path('/' . self::ENV_INSTALL);

        if (!file_exists($baseEnvFilePath)) {
            throw new \Exception('.env.install file does not exist. Please make sure you copied all files to the server.');
        }

        if (!is_writable(base_path())) {
            throw new \Exception('Please make sure the web root folder is writable: ' . base_path());
        }

        if (!copy($baseEnvFilePath, $this->getEnvFilePath())) {
            throw new \Exception('Could not create .env file, please check permissions.');
        }

        
        $this->load();

        
        $key = 'base64:' . base64_encode(random_bytes(32));
        config(['app.key' => $key]);
        $this->save(['APP_KEY' => $key]);

        return $this;
    }

    protected function getEnvFilePath()
    {
        return base_path('/' . self::ENV);
    }

    public function get()
    {
        return $this->env;
    }

    
    public function save(array $params): bool
    {
        if (!is_writable($this->getEnvFilePath())) {
            return FALSE;
        }

        
        $variableToString = function($value) {
            $type = gettype($value);

            $string = in_array($type, ['array','object'])
                ? json_encode(array_map(function($v) {
                        return is_string($v) && Str::startsWith($v, ['[','{']) ? json_decode($v) : $v;
                    }, $value), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) 
                : $value;

            
            if ($type == 'string'
                && !Str::contains($string, '"')
                && (preg_match('#^\#[a-f\d]{3,6}$#i', $string) || Str::contains($string, '#'))) {
                $string = '"' . $string . '"';
            } elseif ($type == 'boolean') {
                $string = $value ? 'true' : 'false';
            } else {
                
                if (Str::contains($string, [' ', '#']) && !Str::contains($string, '\\"')) {
                    $string = '"' . addcslashes($string, '"') . '"';
                }
            }

            return $string;
        };

        $this->env = array_map($variableToString, array_merge($this->env, $params));

        return file_put_contents($this->getEnvFilePath(), implode("\n", array_map(function ($key, $value) {
            return $key . '=' . $value;
        }, array_keys($this->env), $this->env)));
    }
}
