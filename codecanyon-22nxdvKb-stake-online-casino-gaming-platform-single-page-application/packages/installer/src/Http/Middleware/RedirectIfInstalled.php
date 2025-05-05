<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   RedirectIfInstalled.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace Packages\Installer\Http\Middleware;

use Closure;

class RedirectIfInstalled
{
    
    public function handle($request, Closure $next)
    {
        
        
        if (file_exists(storage_path('installed')) && !$request->session()->has('app_redirect')) {
            return redirect('/');
        }

        return $next($request);
    }
}
