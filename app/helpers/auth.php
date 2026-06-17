<?php
/**
 * StockVision - Authentication & Session Helpers
 * Session management
 */

/**
 * Check if user is currently logged in
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Require authentication — redirect to login if not authenticated
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        setFlash('warning', 'Please log in to continue.');
        redirect('/login');
    }
}

/**
 * Get current user's ID
 */
function currentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user's name
 */
function currentUserName(): ?string
{
    return $_SESSION['user_name'] ?? null;
}

/**
 * Get current user's email
 */
function currentUserEmail(): ?string
{
    return $_SESSION['user_email'] ?? null;
}

/**
 * Set session data after successful login
 */
function setSession(array $user): void
{
    $_SESSION['user_id']     = (int)$user['id'];
    $_SESSION['user_name']   = $user['name'];
    $_SESSION['user_email']  = $user['email'];
}

/**
 * Destroy session and clear all data
 */
function destroySession(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
