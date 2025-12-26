<?php

namespace App\Repositories\Mock;

use App\Data\PlazaUser;
use App\Repositories\Contracts\PlazaUserRepositoryInterface;

class MockPlazaUserRepository implements PlazaUserRepositoryInterface
{
    private array $users = [];
    private int $nextId = 1;

    public function __construct()
    {
        // #region agent log
        try {
            $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
            @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'D','location'=>'MockPlazaUserRepository::__construct:14','message'=>'Repository constructor entry','data'=>[],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        } catch (\Throwable $e) {}
        // #endregion
        
        try {
            $this->initializeMockData();
            
            // #region agent log
            try {
                $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
                @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'D','location'=>'MockPlazaUserRepository::__construct:22','message'=>'Mock data initialized','data'=>['userCount'=>count($this->users)],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            } catch (\Throwable $e) {}
            // #endregion
        } catch (\Exception $e) {
            // #region agent log
            try {
                $logPath = __DIR__ . '/../../../storage/logs/plaza_debug.log';
                @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'D','location'=>'MockPlazaUserRepository::__construct:28','message'=>'Mock data initialization error','data'=>['error'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            } catch (\Throwable $logErr) {}
            // #endregion
            throw $e;
        }
    }

    private function initializeMockData(): void
    {
        try {
            // Usuario 1: Owner activo
            $this->users[1] = new PlazaUser(
                id: 1,
                email: 'owner@example.com',
                passwordHash: password_hash('password123', PASSWORD_BCRYPT),
                fullName: 'Juan Pérez',
                status: 'active',
                createdAt: new \DateTime('2024-01-01 10:00:00')
            );
        } catch (\Throwable $e) {
            // Si hay error, intentar sin named arguments (compatibilidad)
            $this->users[1] = new PlazaUser(
                1,
                'owner@example.com',
                password_hash('password123', PASSWORD_BCRYPT),
                'Juan Pérez',
                'active',
                null,
                null,
                null,
                new \DateTime('2024-01-01 10:00:00'),
                0,
                null,
                null,
                new \DateTime('2024-01-01 10:00:00')
            );
        }

        // Usuario 2: Shop Manager activo
        $this->users[2] = new PlazaUser(
            id: 2,
            email: 'manager@example.com',
            passwordHash: password_hash('password123', PASSWORD_BCRYPT),
            fullName: 'María González',
            status: 'active',
            createdAt: new \DateTime('2024-01-02 10:00:00')
        );

        // Usuario 3: Logistics activo
        $this->users[3] = new PlazaUser(
            id: 3,
            email: 'logistics@example.com',
            passwordHash: password_hash('password123', PASSWORD_BCRYPT),
            fullName: 'Carlos Rodríguez',
            status: 'active',
            createdAt: new \DateTime('2024-01-03 10:00:00')
        );

        // Usuario 4: Editor activo
        $this->users[4] = new PlazaUser(
            id: 4,
            email: 'editor@example.com',
            passwordHash: password_hash('password123', PASSWORD_BCRYPT),
            fullName: 'Ana Martínez',
            status: 'active',
            createdAt: new \DateTime('2024-01-04 10:00:00')
        );

        // Usuario 5: Usuario pendiente (invitado)
        $this->users[5] = new PlazaUser(
            id: 5,
            email: 'pending@example.com',
            passwordHash: password_hash('password123', PASSWORD_BCRYPT),
            fullName: 'Pedro Sánchez',
            status: 'pending',
            verificationToken: 'token_pending_123',
            tokenExpiresAt: new \DateTime('+48 hours'),
            createdAt: new \DateTime('2024-01-05 10:00:00')
        );

        $this->nextId = 6;
    }

    public function findById(int $id): ?PlazaUser
    {
        return $this->users[$id] ?? null;
    }

    public function findByEmail(string $email): ?PlazaUser
    {
        foreach ($this->users as $user) {
            if ($user->email === $email) {
                return $user;
            }
        }
        return null;
    }

    public function create(array $data): PlazaUser
    {
        $user = new PlazaUser(
            id: $this->nextId++,
            email: $data['email'],
            passwordHash: $data['password_hash'] ?? password_hash($data['password'] ?? '', PASSWORD_BCRYPT),
            fullName: $data['full_name'] ?? null,
            status: $data['status'] ?? 'pending',
            verificationToken: $data['verification_token'] ?? null,
            resetToken: $data['reset_token'] ?? null,
            tokenExpiresAt: isset($data['token_expires_at']) 
                ? new \DateTime($data['token_expires_at']) 
                : null,
            failedLoginAttempts: $data['failed_login_attempts'] ?? 0,
            lockoutUntil: isset($data['lockout_until']) 
                ? new \DateTime($data['lockout_until']) 
                : null,
            lastLoginAt: isset($data['last_login_at']) 
                ? new \DateTime($data['last_login_at']) 
                : null,
            createdAt: new \DateTime()
        );

        $this->users[$user->id] = $user;
        return $user;
    }

    public function update(int $id, array $data): ?PlazaUser
    {
        if (!isset($this->users[$id])) {
            return null;
        }

        $user = $this->users[$id];
        $updatedData = [
            'id' => $user->id,
            'email' => $data['email'] ?? $user->email,
            'passwordHash' => $data['password_hash'] ?? $user->passwordHash,
            'fullName' => $data['full_name'] ?? $user->fullName,
            'status' => $data['status'] ?? $user->status,
            'verificationToken' => $data['verification_token'] ?? $user->verificationToken,
            'resetToken' => $data['reset_token'] ?? $user->resetToken,
            'tokenExpiresAt' => isset($data['token_expires_at']) 
                ? new \DateTime($data['token_expires_at']) 
                : $user->tokenExpiresAt,
            'failedLoginAttempts' => $data['failed_login_attempts'] ?? $user->failedLoginAttempts,
            'lockoutUntil' => isset($data['lockout_until']) 
                ? new \DateTime($data['lockout_until']) 
                : $user->lockoutUntil,
            'lastLoginAt' => isset($data['last_login_at']) 
                ? new \DateTime($data['last_login_at']) 
                : $user->lastLoginAt,
            'createdAt' => $user->createdAt
        ];

        $this->users[$id] = new PlazaUser(...$updatedData);
        return $this->users[$id];
    }

    public function delete(int $id): bool
    {
        if (isset($this->users[$id])) {
            unset($this->users[$id]);
            return true;
        }
        return false;
    }

    public function incrementFailedAttempts(int $userId): void
    {
        $user = $this->findById($userId);
        if ($user) {
            $this->update($userId, [
                'failed_login_attempts' => $user->failedLoginAttempts + 1
            ]);
        }
    }

    public function resetFailedAttempts(int $userId): void
    {
        $this->update($userId, ['failed_login_attempts' => 0]);
    }

    public function setLockout(int $userId, \DateTime $until): void
    {
        $this->update($userId, ['lockout_until' => $until->format('Y-m-d H:i:s')]);
    }

    public function clearLockout(int $userId): void
    {
        $this->update($userId, ['lockout_until' => null]);
    }

    public function updateLastLogin(int $userId): void
    {
        $this->update($userId, ['last_login_at' => (new \DateTime())->format('Y-m-d H:i:s')]);
    }
}

