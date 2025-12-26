<?php

namespace App\Services;

use App\Data\PlazaUser;
use App\Repositories\Contracts\PlazaAuthAuditRepositoryInterface;
use App\Repositories\Contracts\PlazaUserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PlazaAuthService
{
    private const MAX_FAILED_ATTEMPTS = 5;
    private const LOCKOUT_DURATION_MINUTES = 30;
    private const VERIFICATION_TOKEN_EXPIRY_HOURS = 48;
    private const RESET_TOKEN_EXPIRY_HOURS = 1;

    public function __construct(
        private PlazaUserRepositoryInterface $userRepository,
        private PlazaAuthAuditRepositoryInterface $auditRepository
    ) {
        // #region agent log
        try {
            $logPath = __DIR__ . '/../../storage/logs/plaza_debug.log';
            @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'B','location'=>'PlazaAuthService::__construct:18','message'=>'PlazaAuthService constructor entry','data'=>[],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        } catch (\Throwable $e) {}
        // #endregion
    }

    /**
     * Autenticar usuario con email y contraseña
     */
    public function authenticate(string $email, string $password, string $ipAddress, ?string $userAgent = null): ?PlazaUser
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            $this->logAudit(null, 'login_failed', $ipAddress, $userAgent, [
                'email' => $email,
                'reason' => 'user_not_found'
            ]);
            return null;
        }

        // Verificar si el usuario puede intentar login
        if (!$user->canAttemptLogin()) {
            $this->logAudit($user->id, 'login_failed', $ipAddress, $userAgent, [
                'reason' => $user->isSuspended() ? 'account_suspended' : 'account_locked'
            ]);
            return null;
        }

        // Verificar contraseña
        if (!Hash::check($password, $user->passwordHash)) {
            $this->handleFailedLogin($user, $ipAddress, $userAgent);
            return null;
        }

        // Login exitoso
        $this->userRepository->resetFailedAttempts($user->id);
        $this->userRepository->clearLockout($user->id);
        $this->userRepository->updateLastLogin($user->id);

        $this->logAudit($user->id, 'login_success', $ipAddress, $userAgent);

        return $user;
    }

    /**
     * Manejar intento de login fallido
     */
    private function handleFailedLogin(PlazaUser $user, string $ipAddress, ?string $userAgent): void
    {
        $this->userRepository->incrementFailedAttempts($user->id);
        $updatedUser = $this->userRepository->findById($user->id);

        if ($updatedUser && $updatedUser->failedLoginAttempts >= self::MAX_FAILED_ATTEMPTS) {
            $lockoutUntil = (new \DateTime())->modify('+' . self::LOCKOUT_DURATION_MINUTES . ' minutes');
            $this->userRepository->setLockout($user->id, $lockoutUntil);
        }

        $this->logAudit($user->id, 'login_failed', $ipAddress, $userAgent, [
            'failed_attempts' => $updatedUser->failedLoginAttempts ?? $user->failedLoginAttempts
        ]);
    }

    /**
     * Generar token de verificación para invitación
     */
    public function generateVerificationToken(int $userId): string
    {
        $token = Str::random(64);
        $expiresAt = (new \DateTime())->modify('+' . self::VERIFICATION_TOKEN_EXPIRY_HOURS . ' hours');

        $this->userRepository->update($userId, [
            'verification_token' => $token,
            'token_expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);

        return $token;
    }

    /**
     * Verificar token de verificación
     */
    public function verifyToken(int $userId, string $token): bool
    {
        $user = $this->userRepository->findById($userId);

        if (!$user || $user->verificationToken !== $token) {
            return false;
        }

        if ($user->tokenExpiresAt && $user->tokenExpiresAt < new \DateTime()) {
            return false;
        }

        return true;
    }

    /**
     * Activar cuenta después de verificación
     */
    public function activateAccount(int $userId, string $password, string $ipAddress, ?string $userAgent = null): bool
    {
        $user = $this->userRepository->findById($userId);

        if (!$user || $user->status !== 'pending') {
            return false;
        }

        $this->userRepository->update($userId, [
            'status' => 'active',
            'password_hash' => Hash::make($password),
            'verification_token' => null,
            'token_expires_at' => null
        ]);

        $this->logAudit($userId, 'account_activated', $ipAddress, $userAgent);

        return true;
    }

    /**
     * Generar token de reset de contraseña
     */
    public function generateResetToken(string $email): ?string
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !$user->isActive()) {
            return null;
        }

        $token = Str::random(64);
        $expiresAt = (new \DateTime())->modify('+' . self::RESET_TOKEN_EXPIRY_HOURS . ' hours');

        $this->userRepository->update($user->id, [
            'reset_token' => $token,
            'token_expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);

        $ipAddress = request()->ip() ?? '0.0.0.0';
        $userAgent = request()->userAgent();
        $this->logAudit($user->id, 'password_reset_requested', $ipAddress, $userAgent);

        return $token;
    }

    /**
     * Resetear contraseña con token
     */
    public function resetPassword(string $email, string $token, string $newPassword, string $ipAddress, ?string $userAgent = null): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || $user->resetToken !== $token) {
            return false;
        }

        if ($user->tokenExpiresAt && $user->tokenExpiresAt < new \DateTime()) {
            return false;
        }

        $this->userRepository->update($user->id, [
            'password_hash' => Hash::make($newPassword),
            'reset_token' => null,
            'token_expires_at' => null
        ]);

        $this->logAudit($user->id, 'password_reset', $ipAddress, $userAgent);

        return true;
    }

    /**
     * Cambiar contraseña (usuario autenticado)
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword, string $ipAddress, ?string $userAgent = null): bool
    {
        $user = $this->userRepository->findById($userId);

        if (!$user || !Hash::check($currentPassword, $user->passwordHash)) {
            return false;
        }

        $this->userRepository->update($userId, [
            'password_hash' => Hash::make($newPassword)
        ]);

        $this->logAudit($userId, 'password_changed', $ipAddress, $userAgent);

        return true;
    }

    /**
     * Registrar evento de auditoría
     */
    private function logAudit(?int $userId, string $eventType, string $ipAddress, ?string $userAgent, ?array $metadata = null): void
    {
        $this->auditRepository->create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'metadata' => $metadata
        ]);
    }
}

