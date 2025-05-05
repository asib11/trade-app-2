<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   LicenseService.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace App\Services;

use App\Helpers\Utils;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LicenseService
{
    protected function url(string $uri): string
    {
        return config('app.api.products.base_url') . $uri;
    }

    public function register($code, $email, $hash = NULL, $bundle = FALSE)
    {
        $response = Http::post($this->url(sprintf('licenses/register%s', $bundle ? '-bundle' : '')), [
            'code' => $code,
            'email' => $email,
            'domain' => request()->getHost(),
            'hash' => $bundle ? config('app.hashb') : ($hash ?: config('app.hash'))
        ]);

        if (!$response->ok()) {
            Log::error($response->body());
            return ['success' => FALSE, 'message' => __('There was an error while processing your request.')];
        }

        return $response->json();
    }

    public function download($code, $email, $hash = NULL, $version = NULL): array
    {
        $response = Http::withOptions(['stream' => true])->post($this->url('products/download'), [
            'code' => $code,
            'email' => $email,
            'domain' => request()->getHost(),
            'hash' => $hash ?: config('app.hash'),
            'version' => $version ?: config('app.version'),
            'uname' => php_uname()
        ]);

        if (!$response->ok()) {
            Log::error($response->body());

            return [
                'success' => FALSE,
                'message' => __('There was an error while processing your request.')
            ];
        }

        if ($response->header('Content-Type') !== 'application/zip') {
            return array_merge($response->json(), ['success' => FALSE]);
        }

        $disk = Storage::disk('local');
        $fileName = Utils::generateRandomString(16) . '.zip';

        
        $disk->put($fileName, $response->getBody());

        Utils::unzip($disk->path($fileName));

        return [
            'success' => TRUE,
            'message' => $response->header('Security-Hash')
        ];
    }

    public function record(): void
    {
        Http::post($this->url('installations/register'), [
            'hash' => config('app.hash'),
            'version' => config('app.version'),
            'domain' => request()->getHost(),
            'info' => [
                'code' => env('FP_CODE'),
                'email' => env('FP_EMAIL'),
                'php' => PHP_VERSION,
                'uname' => php_uname(),
            ],
        ]);
    }
}
