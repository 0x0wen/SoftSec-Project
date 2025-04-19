<?php

namespace src\utils;

/**
 * A utility class to handle rate limiting for authentication attempts
 */
class RateLimiter
{
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 300;
    private const ATTEMPT_EXPIRY = 900;
    
    /**
     * Check if an IP address is currently rate limited
     * 
     * @param string $ip 
     * @param string $key
     * @return bool
     */
    public static function isLimited(string $ip, string $key = 'login'): bool
    {
        $lockoutKey = self::getLockoutKey($ip, $key);
        
        // Check if IP is locked out
        if (isset($_SESSION[$lockoutKey])) {
            $lockoutExpiry = $_SESSION[$lockoutKey];
            
            if (time() >= $lockoutExpiry) {
                self::clearLockout($ip, $key);
                return false;
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Record a failed authentication attempt
     * 
     * @param string $ip
     * @param string $key
     * @return array
     */
    public static function recordFailedAttempt(string $ip, string $key = 'login'): array
    {
        $attemptsKey = self::getAttemptsKey($ip, $key);
        $lockoutKey = self::getLockoutKey($ip, $key);
        
        if (!isset($_SESSION[$attemptsKey])) {
            $_SESSION[$attemptsKey] = [
                'count' => 0,
                'expire_at' => time() + self::ATTEMPT_EXPIRY
            ];
        }
        
        if (time() >= $_SESSION[$attemptsKey]['expire_at']) {
            $_SESSION[$attemptsKey] = [
                'count' => 0,
                'expire_at' => time() + self::ATTEMPT_EXPIRY
            ];
        }
        
        $_SESSION[$attemptsKey]['count']++;
        
        if ($_SESSION[$attemptsKey]['count'] >= self::MAX_ATTEMPTS) {
            $lockoutExpiry = time() + self::LOCKOUT_TIME;
            $_SESSION[$lockoutKey] = $lockoutExpiry;
            
            return [
                'limited' => true,
                'attempts' => $_SESSION[$attemptsKey]['count'],
                'max_attempts' => self::MAX_ATTEMPTS,
                'lockout_time' => self::LOCKOUT_TIME,
                'lockout_expires' => self::getReadableTimeRemaining($lockoutExpiry)
            ];
        }
        
        return [
            'limited' => false,
            'attempts' => $_SESSION[$attemptsKey]['count'],
            'max_attempts' => self::MAX_ATTEMPTS,
            'remaining' => self::MAX_ATTEMPTS - $_SESSION[$attemptsKey]['count']
        ];
    }
    
    /**
     * Reset attempts counter after successful authentication
     * 
     * @param string $ip
     * @param string $key
     * @return void
     */
    public static function resetAttempts(string $ip, string $key = 'login'): void
    {
        $attemptsKey = self::getAttemptsKey($ip, $key);
        unset($_SESSION[$attemptsKey]);
    }
    
    /**
     * Clear lockout status for an IP
     * 
     * @param string $ip
     * @param string $key
     * @return void
     */
    public static function clearLockout(string $ip, string $key = 'login'): void
    {
        $lockoutKey = self::getLockoutKey($ip, $key);
        unset($_SESSION[$lockoutKey]);
    }
    
    /**
     * Get remaining attempts before lockout
     * 
     * @param string $ip
     * @param string $key
     * @return int
     */
    public static function getRemainingAttempts(string $ip, string $key = 'login'): int
    {
        $attemptsKey = self::getAttemptsKey($ip, $key);
        
        if (!isset($_SESSION[$attemptsKey])) {
            return self::MAX_ATTEMPTS;
        }
        
        if (time() >= $_SESSION[$attemptsKey]['expire_at']) {
            self::resetAttempts($ip, $key);
            return self::MAX_ATTEMPTS;
        }
        
        return max(0, self::MAX_ATTEMPTS - $_SESSION[$attemptsKey]['count']);
    }
    
    /**
     * Get time remaining for lockout in a human-readable format
     * 
     * @param string $ip
     * @param string $key
     * @return string
     */
    public static function getLockoutTimeRemaining(string $ip, string $key = 'login'): string
    {
        $lockoutKey = self::getLockoutKey($ip, $key);
        
        if (!isset($_SESSION[$lockoutKey])) {
            return '';
        }
        
        $lockoutExpiry = $_SESSION[$lockoutKey];
        
        return self::getReadableTimeRemaining($lockoutExpiry);
    }
    
    /**
     * Get a unique key for storing attempts based on IP and feature
     * 
     * @param string $ip
     * @param string $key
     * @return string
     */
    private static function getAttemptsKey(string $ip, string $key): string
    {
        return "rate_limit_{$key}_attempts_" . md5($ip);
    }
    
    /**
     * Get a unique key for storing lockout status based on IP and feature
     * 
     * @param string $ip
     * @param string $key
     * @return string
     */
    private static function getLockoutKey(string $ip, string $key): string
    {
        return "rate_limit_{$key}_lockout_" . md5($ip);
    }
    
    /**
     * Convert timestamp to human-readable time remaining
     * 
     * @param int $timestamp
     * @return string
     */
    private static function getReadableTimeRemaining(int $timestamp): string
    {
        $remaining = $timestamp - time();
        
        if ($remaining <= 0) {
            return 'now';
        }
        
        if ($remaining < 60) {
            return $remaining . ' seconds';
        }
        
        if ($remaining < 3600) {
            $minutes = ceil($remaining / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '');
        }
        
        $hours = ceil($remaining / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '');
    }
}