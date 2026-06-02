<?php
/**
 * StockVision - Activity Logging Helper
 * Track important system actions in the activity_logs table
 */

/**
 * Log an activity to the database
 *
 * @param string      $action     Action identifier (e.g., 'product.created', 'user.login')
 * @param string|null $entityType Entity type (e.g., 'product', 'supplier')
 * @param int|null    $entityId   Entity ID
 * @param string      $description Human-readable description
 */
function logActivity(
    string  $action,
    ?string $entityType = null,
    ?int    $entityId = null,
    string  $description = ''
): void {
    try {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO `activity_logs` 
                (`user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`)
            VALUES 
                (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            currentUserId(),
            $action,
            $entityType,
            $entityId,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        ]);
    } catch (\Throwable $e) {
        // Silently fail — logging should never break the application
        error_log('Activity log failed: ' . $e->getMessage());
    }
}
