<?php

declare(strict_types=1);

namespace Lilyweb\Core;

use PDO;
use Exception;

/**
 * Single-Owner Authentication Guard for Lily Interiors CMS.
 *
 * Enforces:
 * - Single-owner model (no public registration, no role selectors)
 * - Rate limiting (max 5 failed attempts per 15 mins)
 * - Session regeneration on login
 * - Audit logging
 */
final class Auth
{
    private const SESSION_KEY = 'lilyweb_admin_user_id';
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 900; // 15 minutes

    private static ?array $cachedUser = null;

    public static function check(): bool
    {
        return self::id() !== null;
    }

    public static function id(): ?int
    {
        $id = Session::get(self::SESSION_KEY);
        return is_numeric($id) ? (int) $id : null;
    }

    public static function user(): ?array
    {
        if (self::$cachedUser !== null) {
            return self::$cachedUser;
        }

        $id = self::id();
        if ($id === null) {
            return null;
        }

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                SELECT `id`, `username`, `email`, `display_name`, `is_active`, `last_login_at`, `created_at`
                FROM `lilyweb_users`
                WHERE `id` = :id AND `is_active` = 1
                LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                self::$cachedUser = $user;
                return $user;
            }
        } catch (Exception $e) {
            Logger::error('Auth user fetch error: ' . $e->getMessage());
        }

        // Invalid user or deactivated
        self::logout();
        return null;
    }

    public static function isLockedOut(string $ip): array
    {
        $attempts = Session::get('_login_rate_' . md5($ip), []);
        $count = (int) ($attempts['count'] ?? 0);
        $lastTime = (int) ($attempts['last_time'] ?? 0);

        if ($count >= self::MAX_ATTEMPTS) {
            $elapsed = time() - $lastTime;
            if ($elapsed < self::LOCKOUT_SECONDS) {
                $remaining = self::LOCKOUT_SECONDS - $elapsed;
                return ['locked' => true, 'remaining_mins' => ceil($remaining / 60)];
            } else {
                // Reset after lockout expires
                Session::forget('_login_rate_' . md5($ip));
            }
        }

        return ['locked' => false, 'remaining_mins' => 0];
    }

    public static function recordFailedAttempt(string $ip): void
    {
        $key = '_login_rate_' . md5($ip);
        $attempts = Session::get($key, ['count' => 0, 'last_time' => 0]);
        $attempts['count'] = ((int) $attempts['count']) + 1;
        $attempts['last_time'] = time();
        Session::put($key, $attempts);
    }

    public static function clearFailedAttempts(string $ip): void
    {
        Session::forget('_login_rate_' . md5($ip));
    }

    public static function attempt(string $login, string $password, string $ip): array
    {
        $lockout = self::isLockedOut($ip);
        if ($lockout['locked']) {
            return [
                'success' => false,
                'message' => "Too many failed login attempts. Please wait {$lockout['remaining_mins']} minute(s) before trying again."
            ];
        }

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                SELECT `id`, `username`, `email`, `password_hash`, `display_name`, `is_active`
                FROM `lilyweb_users`
                WHERE (`username` = :login1 OR `email` = :login2) AND `is_active` = 1
                LIMIT 1
            ");
            $cleanLogin = trim($login);
            $stmt->execute([':login1' => $cleanLogin, ':login2' => $cleanLogin]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful authentication
                self::clearFailedAttempts($ip);

                // Session fixation prevention
                Session::regenerate();
                Session::put(self::SESSION_KEY, (int) $user['id']);
                self::$cachedUser = $user;

                // Update last login timestamp
                $updStmt = $pdo->prepare("UPDATE `lilyweb_users` SET `last_login_at` = NOW() WHERE `id` = :id");
                $updStmt->execute([':id' => $user['id']]);

                // Audit log
                self::logAudit($user['username'], 'login', 'user', (string) $user['id'], $ip);

                return ['success' => true, 'message' => 'Login successful. Welcome back!'];
            }

            // Failed login
            self::recordFailedAttempt($ip);
            return [
                'success' => false,
                'message' => 'Invalid credentials. Please verify your username/email and password.'
            ];

        } catch (Exception $e) {
            Logger::error('Auth attempt error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'A system error occurred. Please try again later.'
            ];
        }
    }

    public static function logout(): void
    {
        $user = self::user();
        if ($user) {
            self::logAudit($user['username'], 'logout', 'user', (string) $user['id'], $_SERVER['REMOTE_ADDR'] ?? '');
        }

        Session::forget(self::SESSION_KEY);
        self::$cachedUser = null;
        Session::regenerate();
    }

    public static function logAudit(string $username, string $action, string $entityType, string $entityId, string $ip, ?array $details = null): void
    {
        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                INSERT INTO `lilyweb_audit_logs` (`username`, `action`, `entity_type`, `entity_id`, `details_json`, `ip_address`)
                VALUES (:u, :a, :et, :eid, :det, :ip)
            ");
            $stmt->execute([
                ':u' => $username,
                ':a' => $action,
                ':et' => $entityType,
                ':eid' => $entityId,
                ':det' => $details ? json_encode($details) : null,
                ':ip' => $ip,
            ]);
        } catch (Exception $e) {
            Logger::error('Audit log write error: ' . $e->getMessage());
        }
    }
}
