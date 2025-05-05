<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   InstallerController.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace Packages\Installer\Http\Controllers;

use App\Facades\Manager;
use App\Helpers\Utils;
use App\Models\User;
use App\Services\DotEnvService;
use App\Repositories\UserRepository;
use Database\Seeders\BonusRuleSeeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;

class InstallerController
{
    private $env;
    private $dotEnvService;
    private $steps = [
        1 => 'License registration',
        2 => 'Database setup',
        3 => 'Admin account',
        4 => 'Completed!',
    ];

    public function __construct(Request $request, DotEnvService $dotEnvService)
    {
        $this->dotEnvService = $dotEnvService;
        $this->env = $this->dotEnvService->get();

        
        
        $storageLink = public_path('storage');
        $langLink = public_path('lang');

        if (!file_exists($storageLink) || !file_exists($langLink)) {
            
            Artisan::call('storage:link');

            if (!file_exists($storageLink)) {
                die('Could not create public/storage --> storage/app/public symbolic link. Please check symlinks are allowed on your server.');
            }

            if (!file_exists($langLink)) {
                die('Could not create public/lang --> resources/lang symbolic link. Please check symlinks are allowed on your server.');
            }
        }
    }

    
    public function view(Request $request, $step)
    {
        
        if ($step > 1 && !$request->session()->has('app_redirect')) {
            return redirect()->route('install.view', ['step' => 1]);
        }

        return view('installer::pages.step' . $step, [
            'step' => $step,
            'title' => $this->steps[$step]
        ]);
    }

    
    public function process(Request $request, $step)
    {
        return call_user_func_array([$this, 'step' . $step], [$request, $step]);
    }

    public function step1(Request $request, $step)
    {
        set_time_limit(1800);

        $response = Manager::download($request->code, $request->email);

        if (!$response['success']) {
            return back()->withInput()->withErrors($response['message'] ?? __('There was an error.'))->with('app_redirect', TRUE);
        }

        $this->dotEnvService->save([FP_CODE => $request->code, FP_EMAIL => $request->email, FP_HASH => $response['message']]);

        return redirect()
            ->route('install.view', ['step' => $step + 1])
            ->with('app_redirect', TRUE);
    }

    public function step2(Request $request, $step)
    {
        try {
            
            new PDO(
                'mysql:host='.$request->host.';port='.$request->port.';dbname='.$request->name,
                $request->username,
                $request->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            
            $this->env['DB_HOST'] = $request->host;
            $this->env['DB_PORT'] = $request->port;
            $this->env['DB_DATABASE'] = $request->name;
            $this->env['DB_USERNAME'] = $request->username;
            $this->env['DB_PASSWORD'] = $request->password;
            $this->dotEnvService->save($this->env);

            
            config(['database.connections.mysql.host' => $request->host]);
            config(['database.connections.mysql.port' => $request->port]);
            config(['database.connections.mysql.database' => $request->name]);
            config(['database.connections.mysql.username' => $request->username]);
            config(['database.connections.mysql.password' => $request->password]);

            
            DB::purge('mysql');

            set_time_limit(1800);
            
            Artisan::call('migrate:fresh', [
                '--force' => TRUE,
                '--seed' => TRUE,
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors($e->getMessage())->with('app_redirect', TRUE);
        }

        return redirect()
            ->route('install.view', ['step' => $step + 1])
            ->with('app_redirect', TRUE);
    }

    public function step3(Request $request, $step)
    {
        try {
            
            $user = UserRepository::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'role'              => User::ROLE_ADMIN,
                'password'          => $request->password,
                'email_verified_at' => Carbon::now(),
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors($e->getMessage())->with('app_redirect', TRUE);
        }

        event(new Registered($user));

        
        $this->env['APP_URL'] = url('/');
        $this->env['APP_DEBUG'] = 'false';
        $this->env['LOG_LEVEL'] = 'error';
        $this->env['SANCTUM_STATEFUL_DOMAINS'] = request()->getHost();
        $this->dotEnvService->save($this->env);

        
        touch(storage_path('installed'));

        Manager::record();

        return redirect()
            ->route('install.view', ['step' => $step + 1])
            ->with('app_redirect', TRUE);
    }
}
