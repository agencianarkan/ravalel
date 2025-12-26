<?php

namespace App\Http\Middleware;

use App\Services\PlazaContextService;
use App\Services\PlazaPermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlazaPermission
{
    public function __construct(
        private PlazaPermissionService $permissionService,
        private PlazaContextService $contextService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $capability): Response
    {
        $userId = $this->contextService->getCurrentUserId();
        $storeId = $this->contextService->getCurrentStoreId();

        if (!$userId || !$storeId) {
            abort(403, 'No tienes acceso a esta tienda');
        }

        if (!$this->permissionService->can($userId, $storeId, $capability)) {
            abort(403, "No tienes permiso para: {$capability}");
        }

        return $next($request);
    }
}

