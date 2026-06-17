<?php
/**
 * StockVision - Input Validation Helpers
 * Server-side validation functions for form inputs
 */

/**
 * Check if a value is present and not empty
 */
function validateRequired(mixed $value, string $fieldName): ?string
{
    if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
        return "{$fieldName} is required.";
    }
    return null;
}

/**
 * Validate email format
 */
function validateEmail(string $email): ?string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address.";
    }
    return null;
}

/**
 * Validate minimum string length
 */
function validateMinLength(string $value, int $min, string $fieldName): ?string
{
    if (mb_strlen(trim($value)) < $min) {
        return "{$fieldName} must be at least {$min} characters.";
    }
    return null;
}

/**
 * Validate maximum string length
 */
function validateMaxLength(string $value, int $max, string $fieldName): ?string
{
    if (mb_strlen(trim($value)) > $max) {
        return "{$fieldName} must not exceed {$max} characters.";
    }
    return null;
}

/**
 * Validate that value is numeric
 */
function validateNumeric(mixed $value, string $fieldName): ?string
{
    if (!is_numeric($value)) {
        return "{$fieldName} must be a number.";
    }
    return null;
}

/**
 * Validate that value is a positive number
 */
function validatePositive(mixed $value, string $fieldName): ?string
{
    if (!is_numeric($value) || floatval($value) < 0) {
        return "{$fieldName} must be a positive number.";
    }
    return null;
}

/**
 * Validate uniqueness in database table
 */
function validateUnique(PDO $db, string $table, string $column, mixed $value, ?int $excludeId = null): ?string
{
    $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = ?";
    $params = [$value];

    if ($excludeId !== null) {
        $sql .= " AND `id` != ?";
        $params[] = $excludeId;
    }

    // For soft-delete tables, only check non-deleted records
    if (in_array($table, ['products', 'categories', 'suppliers'])) {
        $sql .= " AND `deleted_at` IS NULL";
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    if ($stmt->fetchColumn() > 0) {
        $displayColumn = ucfirst(str_replace('_', ' ', $column));
        return "{$displayColumn} already exists.";
    }

    return null;
}

/**
 * Sanitize a string value (trim + escape HTML)
 */
function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}
