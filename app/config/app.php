<?php
/**
 * StockVision - Application Configuration
 * Global constants and settings
 */

// Application info
define('APP_NAME', 'StockVision');
define('APP_VERSION', '1.0.0');

// Auto-detect base URL (works with Laragon virtual hosts and subdirectories)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', $scriptDir === '/' ? '' : rtrim($scriptDir, '/'));

// Pagination
define('ITEMS_PER_PAGE', 15);

// File uploads
define('MAX_IMAGE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('UPLOAD_DIR', __DIR__ . '/../../public/uploads');

// User roles
define('ROLE_ADMIN', 'admin');
define('ROLE_EMPLOYEE', 'employee');
define('ROLE_VIEWER', 'viewer');

// Security
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes in seconds
define('SESSION_LIFETIME', 7200);  // 2 hours in seconds
