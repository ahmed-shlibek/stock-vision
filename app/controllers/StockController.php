<?php
/**
 * StockVision - Stock Controller
 * Handles HTTP requests for Stock Operations (In/Out/History)
 */

class StockController
{
    private StockMovement $stockModel;
    private Product $productModel;
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
        $this->stockModel = new StockMovement($this->db);
        $this->productModel = new Product($this->db);
    }

    /**
     * Display stock movement history
     */
    public function index(): void
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        
        $filters = [
            'type'      => $_GET['type'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to'   => $_GET['date_to'] ?? ''
        ];

        $result = $this->stockModel->getPaginated($page, 15, $filters);

        $pageTitle   = 'Stock Movements';
        $movements   = $result['data'];
        $totalPages  = $result['last_page'];
        $currentPage = $page;

        $content = __DIR__ . '/../../views/stock/index.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Show form to add stock (IN)
     */
    public function inForm(): void
    {
        $pageTitle = 'Stock In';
        $products  = $this->productModel->getActiveProducts();
        
        $content = __DIR__ . '/../../views/stock/in.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Process stock addition
     */
    public function storeIn(): void
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid security token.');
            redirect('/stock/in');
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 0);
        $notes     = trim($_POST['notes'] ?? '');

        // Validation
        if ($productId <= 0) {
            setFlash('error', 'Please select a product.');
            redirect('/stock/in');
        }
        if ($quantity <= 0) {
            setFlash('error', 'Quantity must be greater than zero.');
            redirect('/stock/in');
        }

        try {
            $this->stockModel->record($productId, currentUserId(), 'in', $quantity, $notes);
            setFlash('success', 'Stock added successfully.');
            redirect('/stock');
        } catch (Exception $e) {
            setFlash('error', 'Failed to add stock: ' . $e->getMessage());
            redirect('/stock/in');
        }
    }

    /**
     * Show form to remove stock (OUT)
     */
    public function outForm(): void
    {
        $pageTitle = 'Stock Out';
        $products  = $this->productModel->getActiveProducts();
        
        $content = __DIR__ . '/../../views/stock/out.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Process stock removal
     */
    public function storeOut(): void
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid security token.');
            redirect('/stock/out');
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 0);
        $notes     = trim($_POST['notes'] ?? '');

        // Validation
        if ($productId <= 0) {
            setFlash('error', 'Please select a product.');
            redirect('/stock/out');
        }
        if ($quantity <= 0) {
            setFlash('error', 'Quantity must be greater than zero.');
            redirect('/stock/out');
        }

        try {
            $this->stockModel->record($productId, currentUserId(), 'out', $quantity, $notes);
            setFlash('success', 'Stock removed successfully.');
            redirect('/stock');
        } catch (Exception $e) {
            setFlash('error', $e->getMessage());
            redirect('/stock/out');
        }
    }
}
