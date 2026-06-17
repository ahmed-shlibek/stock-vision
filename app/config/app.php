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

