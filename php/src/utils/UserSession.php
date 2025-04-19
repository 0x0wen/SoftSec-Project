<?php

namespace src\utils;

use src\dao\{UserDao, UserRole};

/**
 * Class abstraction to access the user session
 */

class UserSession
{
    /**
     * Start the session if it is not already started
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_secure' => false, // Set to true if using HTTPS
                'cookie_samesite' => 'Strict'
            ]);
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function getCsrfToken(): ?string
    {
        return $_SESSION['csrf_token'] ?? null;
    }

    public static function regenerateCsrfToken(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    /**
     * Set the user session
     * @param user: UserDao object
     */
    public static function setUser(UserDao $user): void
    {
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ];
    }

    /**
     * Get current user id
     */
    public static function getUserId(): int | null
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }
        return $_SESSION['user']['id'];
    }

    /**
     * Get current user email
     */
    public static function getUserEmail(): string | null
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }
        return $_SESSION['user']['email'];
    }

    /**
     * Get current user role
     */
    public static function getUserRole(): UserRole | null
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }
        return $_SESSION['user']['role'];
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Destroy the session
     */
    public static function destroy(): void
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }
}