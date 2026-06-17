<?php
/**
 * StockVision - Dashboard Controller
 * Dashboard overview with stats, charts, and widgets
 */

class DashboardController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Show dashboard page
     */
    public function index(): void
    {
        requireLogin();

        $stats           = $this->getStats();
        $recentMovements = $this->getRecentMovements(8);
        $lowStockItems   = $this->getLowStockItems(5);
        $trendData       = $this->getStockTrend(14);
        $categoryData    = $this->getCategoryDistribution();

        $pageTitle = 'Dashboard';
        $this->render('dashboard/index', compact(
            'pageTitle', 'stats', 'recentMovements',
            'lowStockItems', 'trendData', 'categoryData'
        ));
    }

    /**
     * Get aggregated dashboard statistics
     */
    private function getStats(): array
    {
        $stats = [];

        $stmt = $this->db->query("SELECT COUNT(*) FROM `products` WHERE `deleted_at` IS NULL");
        $stats['total_products'] = (int)$stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM `categories` WHERE `deleted_at` IS NULL");
        $stats['total_categories'] = (int)$stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM `suppliers` WHERE `deleted_at` IS NULL");
        $stats['total_suppliers'] = (int)$stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COALESCE(SUM(`quantity`), 0) FROM `products` WHERE `deleted_at` IS NULL");
        $stats['total_quantity'] = (int)$stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COALESCE(SUM(`quantity` * `unit_price`), 0) FROM `products` WHERE `deleted_at` IS NULL");
        $stats['total_value'] = (float)$stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM `products` WHERE `quantity` <= `min_stock_level` AND `deleted_at` IS NULL");
        $stats['low_stock_count'] = (int)$stmt->fetchColumn();

        return $stats;
    }

    /**
     * Get top N low-stock items
     */
    private function getLowStockItems(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.sku, p.quantity, p.min_stock_level, p.unit,
                   c.name AS category_name, c.color AS category_color
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            WHERE p.quantity <= p.min_stock_level
              AND p.deleted_at IS NULL
            ORDER BY (p.quantity / GREATEST(p.min_stock_level, 1)) ASC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Get stock trend data (IN vs OUT) for last N days
     */
    private function getStockTrend(int $days = 14): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(created_at) AS date,
                type,
                SUM(quantity) AS total
            FROM `stock_movements`
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(created_at), type
            ORDER BY date ASC
        ");
        $stmt->execute([$days]);
        $rows = $stmt->fetchAll();

        // Build a day-by-day array
        $dateRange = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $dateRange[$d] = ['in' => 0, 'out' => 0];
        }

        foreach ($rows as $row) {
            if (isset($dateRange[$row['date']])) {
                $dateRange[$row['date']][$row['type']] = (int)$row['total'];
            }
        }

        $labels  = [];
        $inData  = [];
        $outData = [];
        foreach ($dateRange as $date => $vals) {
            $labels[]  = date('M j', strtotime($date));
            $inData[]  = $vals['in'];
            $outData[] = $vals['out'];
        }

        return compact('labels', 'inData', 'outData');
    }

    /**
     * Get stock value distribution by category
     */
    private function getCategoryDistribution(): array
    {
        $stmt = $this->db->query("
            SELECT 
                COALESCE(c.name, 'Uncategorized') AS name,
                COALESCE(c.color, '#94a3b8') AS color,
                SUM(p.quantity * p.unit_price) AS value
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            WHERE p.deleted_at IS NULL
            GROUP BY c.id, c.name, c.color
            HAVING value > 0
            ORDER BY value DESC
            LIMIT 8
        ");
        $rows = $stmt->fetchAll();

        $labels = [];
        $values = [];
        $colors = [];
        foreach ($rows as $row) {
            $labels[] = $row['name'];
            $values[] = round((float)$row['value'], 2);
            $colors[] = $row['color'];
        }

        return compact('labels', 'values', 'colors');
    }

    /**
     * Get recent stock movements
     */
    private function getRecentMovements(int $limit = 8): array
    {
        $stmt = $this->db->prepare("
            SELECT sm.*, p.name AS product_name, p.sku, u.name AS user_name
            FROM `stock_movements` sm
            JOIN `products` p ON sm.product_id = p.id
            JOIN `users` u ON sm.user_id = u.id
            ORDER BY sm.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    private function render(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data);
        $content = __DIR__ . '/../../views/' . $view . '.php';
        require __DIR__ . '/../../views/layouts/' . $layout . '.php';
    }
}
