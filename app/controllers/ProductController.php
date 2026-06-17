<?php
/**
 * StockVision - Product Controller
 * Handles HTTP requests for Products
 */

class ProductController
{
    private Product $productModel;
    private Category $categoryModel;
    private Supplier $supplierModel;
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db);
        $this->supplierModel = new Supplier($this->db);
    }

    /**
     * List products
     */
    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');
        $categoryId = !empty($_GET['category']) ? (int)$_GET['category'] : null;

        $result = $this->productModel->getAll($page, 10, $search, $categoryId);
        
        $pageTitle = 'Products';
        $products = $result['data'];
        $categories = $this->categoryModel->getAllList();

        $pagination = [
            'current_page' => $result['current_page'],
            'total_pages'  => $result['pages'],
            'total'        => $result['total'],
            'base_url'     => '/products',
            'query_params' => ['search' => $search, 'category' => $categoryId]
        ];

        $this->render('products/index', compact('pageTitle', 'products', 'categories', 'pagination', 'search', 'categoryId'));
    }

    /**
     * Show single product details
     */
    public function show(int $id): void
    {
        $product = $this->productModel->findById($id);
        if (!$product) {
            setFlash('danger', 'Product not found.');
            redirect('/products');
        }

        $pageTitle = 'Product Details - ' . htmlspecialchars($product['sku']);
        $this->render('products/show', compact('pageTitle', 'product'));
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $pageTitle = 'Add Product';
        $categories = $this->categoryModel->getAllList();
        $suppliers = $this->supplierModel->getAllList();
        $errors = getErrors();
        
        $this->render('products/create', compact('pageTitle', 'categories', 'suppliers', 'errors'));
    }

    /**
     * Store new product
     */
    public function store(): void
    {
        $data = $this->getPostData();

        // Validate
        $errors = $this->validateProductData($data);

        // Handle Image Upload
        if (empty($errors) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image'], $errors);
            if ($imagePath) {
                $data['image'] = $imagePath;
            }
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect('/products/create');
        }

        $id = $this->productModel->create($data);

        // Initial Stock Movement log if quantity > 0
        if ($data['quantity'] > 0) {
            $stmt = $this->db->prepare("INSERT INTO `stock_movements` (`product_id`, `user_id`, `type`, `quantity`, `quantity_before`, `quantity_after`, `notes`) VALUES (?, ?, 'in', ?, 0, ?, 'Initial stock entry')");
            $stmt->execute([$id, $_SESSION['user_id'], $data['quantity'], $data['quantity']]);
        }

        clearOld();
        setFlash('success', 'Product created successfully.');
        redirect('/products');
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $product = $this->productModel->findById($id);
        if (!$product) {
            setFlash('danger', 'Product not found.');
            redirect('/products');
        }

        $pageTitle = 'Edit Product';
        $categories = $this->categoryModel->getAllList();
        $suppliers = $this->supplierModel->getAllList();
        $errors = getErrors();
        
        $this->render('products/edit', compact('pageTitle', 'product', 'categories', 'suppliers', 'errors'));
    }

    /**
     * Update product
     */
    public function update(int $id): void
    {
        $product = $this->productModel->findById($id);
        if (!$product) {
            setFlash('danger', 'Product not found.');
            redirect('/products');
        }

        $data = $this->getPostData();
        // Quantity isn't editable directly through product update (must use stock movements)
        unset($data['quantity']); 

        // Validate
        $errors = $this->validateProductData($data, $id);

        // Keep old image by default
        $data['image'] = $product['image'];

        // Handle Image Upload
        if (empty($errors) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image'], $errors);
            if ($imagePath) {
                $data['image'] = $imagePath;
                // Delete old image if exists
                if ($product['image'] && file_exists(__DIR__ . '/../../public/' . $product['image'])) {
                    unlink(__DIR__ . '/../../public/' . $product['image']);
                }
            }
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect("/products/{$id}/edit");
        }

        $this->productModel->update($id, $data);

        clearOld();
        setFlash('success', 'Product updated successfully.');
        redirect('/products');
    }

    /**
     * Soft delete product
     */
    public function delete(int $id): void
    {
        $product = $this->productModel->findById($id);
        if (!$product) {
            setFlash('danger', 'Product not found.');
            redirect('/products');
        }

        $this->productModel->delete($id);

        setFlash('success', 'Product deleted successfully.');
        redirect('/products');
    }

    // --- Helper Methods ---

    private function getPostData(): array
    {
        return [
            'name'            => trim($_POST['name'] ?? ''),
            'sku'             => trim($_POST['sku'] ?? ''),
            'category_id'     => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'supplier_id'     => !empty($_POST['supplier_id']) ? (int)$_POST['supplier_id'] : null,
            'description'     => trim($_POST['description'] ?? ''),
            'unit_price'      => (float)($_POST['unit_price'] ?? 0),
            'quantity'        => (int)($_POST['quantity'] ?? 0),
            'min_stock_level' => (int)($_POST['min_stock_level'] ?? 10),
            'unit'            => trim($_POST['unit'] ?? 'piece'),
        ];
    }

    private function validateProductData(array $data, ?int $excludeId = null): array
    {
        $errors = [];
        
        if ($err = validateRequired($data['name'], 'Name')) $errors['name'] = $err;
        if ($err = validateRequired($data['sku'], 'SKU')) $errors['sku'] = $err;
        
        if (empty($errors['sku'])) {
            if ($this->productModel->skuExists($data['sku'], $excludeId)) {
                $errors['sku'] = 'This SKU is already in use.';
            }
        }

        if ($data['unit_price'] < 0) $errors['unit_price'] = 'Price cannot be negative.';
        if (isset($data['quantity']) && $data['quantity'] < 0) $errors['quantity'] = 'Quantity cannot be negative.';
        if ($data['min_stock_level'] < 0) $errors['min_stock_level'] = 'Min stock cannot be negative.';

        return $errors;
    }

    private function handleImageUpload(array $file, array &$errors): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            $errors['image'] = 'Only JPG, PNG, and WebP images are allowed.';
            return null;
        }

        if ($file['size'] > $maxSize) {
            $errors['image'] = 'Image size must not exceed 2MB.';
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('prod_') . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'uploads/products/' . $filename;
        } else {
            $errors['image'] = 'Failed to upload image.';
            return null;
        }
    }

    private function render(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data);
        $content = __DIR__ . '/../../views/' . $view . '.php';
        require __DIR__ . '/../../views/layouts/' . $layout . '.php';
    }
}
