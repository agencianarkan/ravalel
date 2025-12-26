<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PlazaAuthService;
use App\Services\PlazaContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlazaAuthController extends Controller
{
    public function __construct(
        private PlazaAuthService $authService,
        private PlazaContextService $contextService
    ) {
        // #region agent log
        try {
            $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
            @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'B','location'=>'PlazaAuthController::__construct:13','message'=>'Controller constructor entry','data'=>['class'=>'PlazaAuthController'],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        } catch (\Throwable $e) {}
        // #endregion
        
        // #region agent log
        try {
            $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
            $authServiceClass = get_class($this->authService);
            $contextServiceClass = get_class($this->contextService);
            @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'B','location'=>'PlazaAuthController::__construct:20','message'=>'Services injected','data'=>['authService'=>$authServiceClass,'contextService'=>$contextServiceClass],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        } catch (\Throwable $e) {
            // #region agent log
            try {
                $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
                @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'B','location'=>'PlazaAuthController::__construct:24','message'=>'Service injection error','data'=>['error'=>$e->getMessage(),'trace'=>$e->getTraceAsString()],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            } catch (\Throwable $logErr) {}
            // #endregion
        }
        // #endregion
    }

    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        // #region agent log
        try {
            $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
            @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'A','location'=>'PlazaAuthController::showLoginForm:22','message'=>'Controller entry','data'=>['method'=>'showLoginForm'],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        } catch (\Throwable $e) {}
        // #endregion
        
        // #region agent log
        try {
            $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
            @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'C','location'=>'PlazaAuthController::showLoginForm:26','message'=>'Before view load','data'=>['view'=>'plaza.auth.login'],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        } catch (\Throwable $e) {}
        // #endregion
        
        try {
            $result = view('plaza.auth.login');
            
            // #region agent log
            try {
                $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
                @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'C','location'=>'PlazaAuthController::showLoginForm:35','message'=>'View loaded successfully','data'=>[],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            } catch (\Throwable $e) {}
            // #endregion
            
            return $result;
        } catch (\Exception $e) {
            // #region agent log
            try {
                $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
                @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'C','location'=>'PlazaAuthController::showLoginForm:42','message'=>'View load error','data'=>['error'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            } catch (\Throwable $logErr) {}
            // #endregion
            throw $e;
        }
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = $this->authService->authenticate(
            $request->email,
            $request->password,
            $request->ip(),
            $request->userAgent()
        );

        if (!$user) {
            return back()->withErrors([
                'email' => 'Credenciales inválidas o cuenta bloqueada.'
            ])->withInput();
        }

        // Establecer usuario en sesión
        session(['plaza_user_id' => $user->id]);

        return redirect()->route('plaza.stores.select')
            ->with('success', '¡Bienvenido! Selecciona una tienda para continuar.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->contextService->clearContext();
        session()->forget('plaza_user_id');

        return redirect()->route('plaza.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }

    /**
     * Mostrar formulario de activación de cuenta
     */
    public function showActivationForm(string $token)
    {
        return view('plaza.auth.activate', ['token' => $token]);
    }

    /**
     * Activar cuenta
     */
    public function activate(Request $request, string $token)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Buscar usuario por email
        $userRepo = app(\App\Repositories\Contracts\PlazaUserRepositoryInterface::class);
        $user = $userRepo->findByEmail($request->email);

        if (!$user || !$this->authService->verifyToken($user->id, $token)) {
            return back()->withErrors(['token' => 'Token inválido o expirado.'])->withInput();
        }

        $activated = $this->authService->activateAccount(
            $user->id,
            $request->password,
            $request->ip(),
            $request->userAgent()
        );

        if (!$activated) {
            return back()->withErrors(['error' => 'No se pudo activar la cuenta.'])->withInput();
        }

        return redirect()->route('plaza.login')
            ->with('success', 'Cuenta activada correctamente. Ya puedes iniciar sesión.');
    }
}

