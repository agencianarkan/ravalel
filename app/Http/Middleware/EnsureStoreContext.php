<?php

namespace App\Http\Middleware;

use App\Services\PlazaContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreContext
{
    public function __construct(
        private PlazaContextService $contextService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $storeId = $this->contextService->getCurrentStoreId();
        $userId = $this->contextService->getCurrentUserId();

        if (!$storeId || !$userId) {
            return redirect()->route('plaza.stores.select')
                ->with('error', 'Debes seleccionar una tienda para continuar');
        }

        return $next($request);
    }
}

