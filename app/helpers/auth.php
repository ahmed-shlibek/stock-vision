<?php
/**
 * StockVision - Authentication & Session Helpers
 * Session management, CSRF protection, role-based access control
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
 * Require specific role(s) — redirect with error if unauthorized
 */
function requireRole(string ...$roles): void
{
    requireLogin();
    if (!in_array($_SESSION['user_role'], $roles, true)) {
        setFlash('danger', 'You do not have permission to access this page.');
        redirect('/');
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
 * Get current user's role
 */
function currentUserRole(): ?string
{
    return $_SESSION['user_role'] ?? null;
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
 * Get current user's avatar
 */
function currentUserAvatar(): ?string
{
    return $_SESSION['user_avatar'] ?? null;
}

/**
 * Check if current user has any of the given roles
 */
function hasRole(string ...$roles): bool
{
    if (!isLoggedIn()) {
        return false;
    }
    return in_array($_SESSION['user_role'], $roles, true);
}

/**
 * Generate CSRF token and store in session
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Return HTML hidden input with CSRF token
 */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Verify CSRF token from POST data
 */
function verifyCsrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (empty($token) || empty($sessionToken)) {
        return false;
    }

    $valid = hash_equals($sessionToken, $token);

    // Regenerate token after verification
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    return $valid;
}

/**
 * Set session data after successful login
 */
function setSession(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_id']     = (int)$user['id'];
    $_SESSION['user_name']   = $user['name'];
    $_SESSION['user_email']  = $user['email'];
    $_SESSION['user_role']   = $user['role'];
    $_SESSION['user_avatar'] = $user['avatar'] ?? null;
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

/**
 * Check login rate limiting
 * Returns true if user is locked out
 */
function isLoginLocked(): bool
{
    $attempts = $_SESSION['login_attempts'] ?? 0;
    $lockoutTime = $_SESSION['login_lockout_until'] ?? 0;

    if ($lockoutTime > time()) {
        return true;
    }

    // Reset if lockout has expired
    if ($lockoutTime > 0 && $lockoutTime <= time()) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['login_lockout_until'] = 0;
    }

    return false;
}

/**
 * Record a failed login attempt
 */
function recordFailedLogin(): void
{
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;

    if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        $_SESSION['login_lockout_until'] = time() + LOGIN_LOCKOUT_TIME;
    }
}

/**
 * Reset login attempts on successful login
 */
function resetLoginAttempts(): void
{
    $_SESSION['login_attempts'] = 0;
    $_SESSION['login_lockout_until'] = 0;
}
