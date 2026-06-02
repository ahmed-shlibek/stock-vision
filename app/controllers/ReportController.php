<?php
/**
 * StockVision - Report Controller
 * Generates inventory, low-stock, and movement reports with CSV export
 */

class ReportController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function index(): void
    {
        requireLogin();
        $pageTitle = 'Reports';
        $content   = __DIR__ . '/../../views/reports/index.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /** Full inventory report */
    public function inventory(): void
    {
        requireLogin();
        $products = $this->db->query("
            SELECT p.*, 
                   c.name AS category_name,
                   s.name AS supplier_name
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            LEFT JOIN `suppliers` s ON p.supplier_id = s.id
            WHERE p.deleted_at IS NULL
            ORDER BY p.name ASC
        ")->fetchAll();

        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportCsv($products, [
                'id', 'name', 'sku', 'barcode', 'category_name', 'supplier_name',
                'unit_price', 'quantity', 'min_stock_level', 'unit', 'is_active'
            ], 'inventory_report_' . date('Ymd') . '.csv');
            return;
        }

        $pageTitle = 'Inventory Report';
        $content   = __DIR__ . '/../../views/reports/inventory.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /** Low-stock / out-of-stock report */
    public function lowStock(): void
    {
        requireLogin();
        $products = $this->db->query("
            SELECT p.*,
                   c.name AS category_name,
                   s.name AS supplier_name, s.email AS supplier_email, s.phone AS supplier_phone
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            LEFT JOIN `suppliers` s ON p.supplier_id = s.id
            WHERE p.quantity <= p.min_stock_level
              AND p.deleted_at IS NULL AND p.is_active = 1
            ORDER BY (p.quantity / GREATEST(p.min_stock_level, 1)) ASC
        ")->fetchAll();

        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportCsv($products, [
                'name', 'sku', 'category_name', 'supplier_name', 'supplier_email',
                'quantity', 'min_stock_level', 'unit'
            ], 'low_stock_report_' . date('Ymd') . '.csv');
            return;
        }

        $pageTitle = 'Low Stock Report';
        $content   = __DIR__ . '/../../views/reports/low_stock.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /** Stock movement history report */
    public function movements(): void
    {
        requireLogin();

        $dateFrom = $_GET['date_from'] ?? date('Y-m-01'); // first of this month
        $dateTo   = $_GET['date_to']   ?? date('Y-m-d');
        $type     = $_GET['type']      ?? '';

        $where  = ["DATE(sm.created_at) BETWEEN :from AND :to"];
        $params = [':from' => $dateFrom, ':to' => $dateTo];

        if (in_array($type, ['in', 'out'])) {
            $where[]       = "sm.type = :type";
            $params[':type'] = $type;
        }

        $whereClause = implode(' AND ', $where);

        $stmt = $this->db->prepare("
            SELECT sm.*, 
                   p.name AS product_name, p.sku,
                   u.name AS user_name
            FROM `stock_movements` sm
            JOIN `products` p ON sm.product_id = p.id
            JOIN `users` u ON sm.user_id = u.id
            WHERE {$whereClause}
            ORDER BY sm.created_at DESC
        ");
        $stmt->execute($params);
        $movements = $stmt->fetchAll();

        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportCsv($movements, [
                'created_at', 'product_name', 'sku', 'type', 'quantity',
                'quantity_before', 'quantity_after', 'user_name', 'notes'
            ], 'movements_report_' . date('Ymd') . '.csv');
            return;
        }

        $pageTitle = 'Movements Report';
        $content   = __DIR__ . '/../../views/reports/movements.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /** Suppliers report */
    public function suppliers(): void
    {
        requireLogin();
        $suppliers = $this->db->query("
            SELECT s.*, COUNT(p.id) AS product_count
            FROM `suppliers` s
            LEFT JOIN `products` p ON p.supplier_id = s.id AND p.deleted_at IS NULL
            WHERE s.deleted_at IS NULL
            GROUP BY s.id
            ORDER BY s.name ASC
        ")->fetchAll();

        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportCsv($suppliers, [
                'name', 'email', 'phone', 'address', 'product_count', 'is_active'
            ], 'suppliers_report_' . date('Ymd') . '.csv');
            return;
        }

        $pageTitle = 'Suppliers Report';
        $content   = __DIR__ . '/../../views/reports/suppliers.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Stream a CSV download
     */
    private function exportCsv(array $data, array $columns, string $filename): void
    {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

        // Header row
        fputcsv($output, array_map(fn($c) => ucwords(str_replace('_', ' ', $c)), $columns));

        // Data rows
        foreach ($data as $row) {
            $csvRow = [];
            foreach ($columns as $col) {
                $csvRow[] = $row[$col] ?? '';
            }
            fputcsv($output, $csvRow);
        }

        fclose($output);
        exit;
    }
}
