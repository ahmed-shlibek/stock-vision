<?php
/**
 * StockVision - Alert Controller
 * Manages low-stock and out-of-stock alerts
 */

class AlertController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Display all low-stock and out-of-stock products
     */
    public function index(): void
    {
        requireLogin();

        $outOfStock = $this->getOutOfStock();
        $lowStock   = $this->getLowStock();

        $pageTitle = 'Stock Alerts';
        $content   = __DIR__ . '/../../views/alerts/index.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * API: Return alert count as JSON (for sidebar badge)
     */
    public function count(): void
    {
        requireLogin();
        $stmt = $this->db->query("
            SELECT COUNT(*) FROM `products`
            WHERE `quantity` <= `min_stock_level`
              AND `deleted_at` IS NULL
              AND `is_active` = 1
        ");
        jsonResponse(['count' => (int)$stmt->fetchColumn()]);
    }

    private function getOutOfStock(): array
    {
        $stmt = $this->db->query("
            SELECT p.*, 
                   c.name AS category_name, c.color AS category_color,
                   s.name AS supplier_name, s.email AS supplier_email
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            LEFT JOIN `suppliers` s ON p.supplier_id = s.id
            WHERE p.quantity = 0 AND p.deleted_at IS NULL AND p.is_active = 1
            ORDER BY p.name ASC
        ");
        return $stmt->fetchAll();
    }

    private function getLowStock(): array
    {
        $stmt = $this->db->query("
            SELECT p.*,
                   c.name AS category_name, c.color AS category_color,
                   s.name AS supplier_name, s.email AS supplier_email
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            LEFT JOIN `suppliers` s ON p.supplier_id = s.id
            WHERE p.quantity > 0 AND p.quantity <= p.min_stock_level
              AND p.deleted_at IS NULL AND p.is_active = 1
            ORDER BY (p.quantity / GREATEST(p.min_stock_level, 1)) ASC
        ");
        return $stmt->fetchAll();
    }
}
