<?php

namespace App\Providers;

use App\Repositories\Contracts\PlazaAuthAuditRepositoryInterface;
use App\Repositories\Contracts\PlazaCapabilityRepositoryInterface;
use App\Repositories\Contracts\PlazaCustomOverrideRepositoryInterface;
use App\Repositories\Contracts\PlazaMembershipRepositoryInterface;
use App\Repositories\Contracts\PlazaRoleDefinitionRepositoryInterface;
use App\Repositories\Contracts\PlazaRoleRepositoryInterface;
use App\Repositories\Contracts\PlazaStoreRepositoryInterface;
use App\Repositories\Contracts\PlazaUserRepositoryInterface;
use App\Repositories\Mock\MockPlazaAuthAuditRepository;
use App\Repositories\Mock\MockPlazaCapabilityRepository;
use App\Repositories\Mock\MockPlazaCustomOverrideRepository;
use App\Repositories\Mock\MockPlazaMembershipRepository;
use App\Repositories\Mock\MockPlazaRoleDefinitionRepository;
use App\Repositories\Mock\MockPlazaRoleRepository;
use App\Repositories\Mock\MockPlazaStoreRepository;
use App\Repositories\Mock\MockPlazaUserRepository;
use Illuminate\Support\ServiceProvider;

class PlazaRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // #region agent log
        try {
            $logPath = __DIR__ . '/../../storage/logs/plaza_debug.log';
            @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'B','location'=>'PlazaRepositoryServiceProvider::register:28','message'=>'Service Provider register entry','data'=>[],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        } catch (\Throwable $e) {}
        // #endregion
        
        try {
            // Registrar repositorios mock
            $this->app->singleton(PlazaUserRepositoryInterface::class, MockPlazaUserRepository::class);
            
            // #region agent log
            try {
                $logPath = __DIR__ . '/../../storage/logs/plaza_debug.log';
                @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'B','location'=>'PlazaRepositoryServiceProvider::register:35','message'=>'Registered PlazaUserRepository','data'=>[],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            } catch (\Throwable $e) {}
            // #endregion
            
            $this->app->singleton(PlazaStoreRepositoryInterface::class, MockPlazaStoreRepository::class);
            $this->app->singleton(PlazaRoleRepositoryInterface::class, MockPlazaRoleRepository::class);
            $this->app->singleton(PlazaCapabilityRepositoryInterface::class, MockPlazaCapabilityRepository::class);
            $this->app->singleton(PlazaRoleDefinitionRepositoryInterface::class, MockPlazaRoleDefinitionRepository::class);
            $this->app->singleton(PlazaMembershipRepositoryInterface::class, MockPlazaMembershipRepository::class);
            $this->app->singleton(PlazaCustomOverrideRepositoryInterface::class, MockPlazaCustomOverrideRepository::class);
            $this->app->singleton(PlazaAuthAuditRepositoryInterface::class, MockPlazaAuditRepository::class);
            
            // #region agent log
            try {
                $logPath = __DIR__ . '/../../storage/logs/plaza_debug.log';
                @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'B','location'=>'PlazaRepositoryServiceProvider::register:45','message'=>'All repositories registered','data'=>[],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            } catch (\Throwable $e) {}
            // #endregion
        } catch (\Exception $e) {
            // #region agent log
            try {
                $logPath = __DIR__ . '/../../storage/logs/plaza_debug.log';
                @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'B','location'=>'PlazaRepositoryServiceProvider::register:50','message'=>'Service Provider registration error','data'=>['error'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine(),'trace'=>$e->getTraceAsString()],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            } catch (\Throwable $logErr) {}
            // #endregion
            throw $e;
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

