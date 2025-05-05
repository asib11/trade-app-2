<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   SubmitButton.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace Packages\Installer\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SubmitButton extends Component
{
    
    public function __construct(public string $text = 'Next')
    {
        
    }

    
    public function render(): View|Closure|string
    {
        return view('installer::components.submit-button');
    }
}
