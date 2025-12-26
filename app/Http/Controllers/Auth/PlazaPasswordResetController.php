<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PlazaAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlazaPasswordResetController extends Controller
{
    public function __construct(
        private PlazaAuthService $authService
    ) {}

    /**
     * Mostrar formulario de solicitud de reset
     */
    public function showRequestForm()
    {
        return view('plaza.auth.forgot-password');
    }

    /**
     * Enviar email de reset (mock - solo devuelve token)
     */
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $token = $this->authService->generateResetToken($request->email);

        if (!$token) {
            return back()->withErrors([
                'email' => 'No encontramos una cuenta activa con ese email.'
            ])->withInput();
        }

        // En producción, aquí se enviaría el email
        // Por ahora, solo mostramos el token en la respuesta (solo para desarrollo)
        return back()->with([
            'success' => 'Si existe una cuenta con ese email, se ha enviado un enlace de recuperación.',
            'dev_token' => $token // Solo para desarrollo/testing
        ]);
    }

    /**
     * Mostrar formulario de reset con token
     */
    public function showResetForm(string $token)
    {
        return view('plaza.auth.reset-password', ['token' => $token]);
    }

    /**
     * Procesar reset de contraseña
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $reset = $this->authService->resetPassword(
            $request->email,
            $request->token,
            $request->password,
            $request->ip(),
            $request->userAgent()
        );

        if (!$reset) {
            return back()->withErrors([
                'token' => 'Token inválido o expirado.'
            ])->withInput();
        }

        return redirect()->route('plaza.login')
            ->with('success', 'Contraseña restablecida correctamente. Ya puedes iniciar sesión.');
    }
}

