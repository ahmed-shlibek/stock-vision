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
 * Validate that value is in allowed list
 */
function validateIn(mixed $value, array $allowed, string $fieldName): ?string
{
    if (!in_array($value, $allowed, true)) {
        return "{$fieldName} contains an invalid value.";
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
 * Validate uploaded image file
 */
function validateImage(array $file): ?string
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null; // Optional — no file uploaded
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "File upload failed. Please try again.";
    }

    if ($file['size'] > MAX_IMAGE_SIZE) {
        $maxMB = MAX_IMAGE_SIZE / (1024 * 1024);
        return "Image must be smaller than {$maxMB}MB.";
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        return "Image must be JPEG, PNG, or WebP format.";
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

/**
 * Handle image upload — save file and return filename
 */
function handleImageUpload(array $file, string $subDir = 'products'): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $uploadDir = UPLOAD_DIR . '/' . $subDir;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_', true) . '.' . strtolower($extension);
    $destination = $uploadDir . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }

    return null;
}

/**
 * Delete an uploaded image
 */
function deleteImage(string $filename, string $subDir = 'products'): bool
{
    $filepath = UPLOAD_DIR . '/' . $subDir . '/' . $filename;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}
