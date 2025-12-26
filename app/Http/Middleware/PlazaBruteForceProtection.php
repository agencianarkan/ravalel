<?php

namespace App\Http\Middleware;

use App\Data\PlazaUser;
use App\Repositories\Contracts\PlazaUserRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PlazaBruteForceProtection
{
    public function __construct(
        private PlazaUserRepositoryInterface $userRepository
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');
        $key = 'plaza_login_attempts:' . $email;

        // Verificar rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'error' => 'Demasiados intentos. Intenta de nuevo en ' . ceil($seconds / 60) . ' minutos.'
            ], 429);
        }

        // Verificar bloqueo de cuenta
        if ($email) {
            $user = $this->userRepository->findByEmail($email);
            if ($user && $user->isLocked()) {
                return response()->json([
                    'error' => 'Tu cuenta está bloqueada temporalmente. Intenta más tarde.'
                ], 423);
            }
        }

        $response = $next($request);

        // Si el login falló, incrementar contador
        if ($response->getStatusCode() === 401 || $response->getStatusCode() === 422) {
            RateLimiter::hit($key, 60 * 15); // 15 minutos
        } else {
            // Si fue exitoso, limpiar contador
            RateLimiter::clear($key);
        }

        return $response;
    }
}

