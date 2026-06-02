<?php
/**
 * StockVision - Response Helpers
 * JSON responses, redirects, flash messages, form repopulation
 */

/**
 * Send a JSON response and exit
 */
function jsonResponse(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Redirect to a path (relative to BASE_URL)
 */
function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

/**
 * Set a flash message (persists for one request)
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Get and clear the flash message
 */
function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Get old form input value for repopulation
 */
function old(string $field, mixed $default = ''): mixed
{
    $value = $_SESSION['old'][$field] ?? $default;
    return $value;
}

/**
 * Store form data for repopulation after validation failure
 */
function setOld(array $data): void
{
    // Don't store sensitive fields
    unset($data['password'], $data['password_confirm'], $data['current_password'], $data['csrf_token']);
    $_SESSION['old'] = $data;
}

/**
 * Clear old form data
 */
function clearOld(): void
{
    unset($_SESSION['old']);
}

/**
 * Get validation errors
 */
function getErrors(): array
{
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);
    return $errors;
}

/**
 * Set validation errors
 */
function setErrors(array $errors): void
{
    $_SESSION['errors'] = $errors;
}

/**
 * Check if a specific field has an error
 */
function hasError(string $field): bool
{
    return isset($_SESSION['errors'][$field]);
}

/**
 * Get error message for a specific field
 */
function getError(string $field): string
{
    return $_SESSION['errors'][$field] ?? '';
}
