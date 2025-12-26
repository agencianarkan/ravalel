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
        // Registrar repositorios mock
        $this->app->singleton(PlazaUserRepositoryInterface::class, MockPlazaUserRepository::class);
        $this->app->singleton(PlazaStoreRepositoryInterface::class, MockPlazaStoreRepository::class);
        $this->app->singleton(PlazaRoleRepositoryInterface::class, MockPlazaRoleRepository::class);
        $this->app->singleton(PlazaCapabilityRepositoryInterface::class, MockPlazaCapabilityRepository::class);
        $this->app->singleton(PlazaRoleDefinitionRepositoryInterface::class, MockPlazaRoleDefinitionRepository::class);
        $this->app->singleton(PlazaMembershipRepositoryInterface::class, MockPlazaMembershipRepository::class);
        $this->app->singleton(PlazaCustomOverrideRepositoryInterface::class, MockPlazaCustomOverrideRepository::class);
        $this->app->singleton(PlazaAuthAuditRepositoryInterface::class, MockPlazaAuthAuditRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

