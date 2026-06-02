<?php
/**
 * StockVision - Analytics Controller
 * Provides data endpoints for charts and the analytics dashboard page
 */

class AnalyticsController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function index(): void
    {
        requireLogin();
        $pageTitle = 'Analytics';
        $content   = __DIR__ . '/../../views/analytics/index.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /** API: Stock IN vs OUT per day for last 30 days */
    public function stockTrend(): void
    {
        requireLogin();
        $stmt = $this->db->query("
            SELECT DATE(created_at) AS date, type, SUM(quantity) AS total
            FROM `stock_movements`
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at), type
            ORDER BY date ASC
        ");
        $rows = $stmt->fetchAll();

        $dateRange = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $dateRange[$d] = ['in' => 0, 'out' => 0];
        }
        foreach ($rows as $row) {
            if (isset($dateRange[$row['date']])) {
                $dateRange[$row['date']][$row['type']] = (int)$row['total'];
            }
        }

        $labels = $inData = $outData = [];
        foreach ($dateRange as $date => $vals) {
            $labels[]  = date('M j', strtotime($date));
            $inData[]  = $vals['in'];
            $outData[] = $vals['out'];
        }
        jsonResponse(compact('labels', 'inData', 'outData'));
    }

    /** API: Inventory value grouped by category */
    public function byCategory(): void
    {
        requireLogin();
        $stmt = $this->db->query("
            SELECT COALESCE(c.name, 'Uncategorized') AS name,
                   COALESCE(c.color, '#94a3b8') AS color,
                   SUM(p.quantity * p.unit_price) AS value,
                   SUM(p.quantity) AS qty,
                   COUNT(p.id) AS product_count
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            WHERE p.deleted_at IS NULL AND p.is_active = 1
            GROUP BY c.id, c.name, c.color
            ORDER BY value DESC
        ");
        jsonResponse($stmt->fetchAll());
    }

    /** API: Top 10 most active products by movement count */
    public function topMoving(): void
    {
        requireLogin();
        $stmt = $this->db->query("
            SELECT p.name, p.sku, p.unit,
                   COUNT(sm.id) AS movement_count,
                   SUM(CASE WHEN sm.type = 'in' THEN sm.quantity ELSE 0 END) AS total_in,
                   SUM(CASE WHEN sm.type = 'out' THEN sm.quantity ELSE 0 END) AS total_out
            FROM `stock_movements` sm
            JOIN `products` p ON sm.product_id = p.id
            WHERE sm.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY p.id, p.name, p.sku, p.unit
            ORDER BY movement_count DESC
            LIMIT 10
        ");
        jsonResponse($stmt->fetchAll());
    }

    /** API: Monthly IN vs OUT totals for last 6 months */
    public function monthly(): void
    {
        requireLogin();
        $stmt = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month,
                   DATE_FORMAT(created_at, '%b %Y') AS label,
                   type,
                   SUM(quantity) AS total
            FROM `stock_movements`
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m'), type
            ORDER BY month ASC
        ");
        $rows = $stmt->fetchAll();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = date('Y-m', strtotime("-{$i} months"));
            $months[$key] = ['label' => date('M Y', strtotime("-{$i} months")), 'in' => 0, 'out' => 0];
        }
        foreach ($rows as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']][$row['type']] = (int)$row['total'];
            }
        }

        $labels = $inData = $outData = [];
        foreach ($months as $vals) {
            $labels[]  = $vals['label'];
            $inData[]  = $vals['in'];
            $outData[] = $vals['out'];
        }
        jsonResponse(compact('labels', 'inData', 'outData'));
    }

    /** API: Total inventory value */
    public function inventoryValue(): void
    {
        requireLogin();
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(quantity * unit_price), 0) AS total_value,
                   COUNT(*) AS total_products,
                   SUM(quantity) AS total_units
            FROM `products`
            WHERE deleted_at IS NULL AND is_active = 1
        ");
        jsonResponse($stmt->fetch());
    }
}
