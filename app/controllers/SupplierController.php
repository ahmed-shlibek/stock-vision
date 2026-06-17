<?php
/**
 * StockVision - Supplier Controller
 * Handles HTTP requests for Suppliers
 */

class SupplierController
{
    private Supplier $supplierModel;
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
        $this->supplierModel = new Supplier($this->db);
    }

    /**
     * List suppliers
     */
    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->supplierModel->getAll($page);

        $pageTitle = 'Suppliers';
        $suppliers = $result['data'];
        $pagination = [
            'current_page' => $result['current_page'],
            'total_pages'  => $result['pages'],
            'total'        => $result['total'],
            'base_url'     => '/suppliers',
        ];

        $this->render('suppliers/index', compact('pageTitle', 'suppliers', 'pagination'));
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $pageTitle = 'Add Supplier';
        $errors = getErrors();
        $this->render('suppliers/create', compact('pageTitle', 'errors'));
    }

    /**
     * Store new supplier
     */
    public function store(): void
    {
        $data = [
            'name'      => trim($_POST['name'] ?? ''),
            'phone'     => trim($_POST['phone'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'address'   => trim($_POST['address'] ?? ''),
        ];

        // Validate
        $errors = [];
        if ($err = validateRequired($data['name'], 'Name')) $errors['name'] = $err;
        if ($err = validateMaxLength($data['name'], 100, 'Name')) $errors['name'] = $err;
        if (!empty($data['email'])) {
            if ($err = validateEmail($data['email'])) $errors['email'] = $err;
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect('/suppliers/create');
        }

        $id = $this->supplierModel->create($data);

        clearOld();
        setFlash('success', 'Supplier created successfully.');
        redirect('/suppliers');
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $supplier = $this->supplierModel->findById($id);
        if (!$supplier) {
            setFlash('danger', 'Supplier not found.');
            redirect('/suppliers');
        }

        $pageTitle = 'Edit Supplier';
        $errors = getErrors();
        $this->render('suppliers/edit', compact('pageTitle', 'supplier', 'errors'));
    }

    /**
     * Update supplier
     */
    public function update(int $id): void
    {
        $supplier = $this->supplierModel->findById($id);
        if (!$supplier) {
            setFlash('danger', 'Supplier not found.');
            redirect('/suppliers');
        }

        $data = [
            'name'      => trim($_POST['name'] ?? ''),
            'phone'     => trim($_POST['phone'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'address'   => trim($_POST['address'] ?? ''),
        ];

        // Validate
        $errors = [];
        if ($err = validateRequired($data['name'], 'Name')) $errors['name'] = $err;
        if (!empty($data['email'])) {
            if ($err = validateEmail($data['email'])) $errors['email'] = $err;
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect("/suppliers/{$id}/edit");
        }

        $this->supplierModel->update($id, $data);

        clearOld();
        setFlash('success', 'Supplier updated successfully.');
        redirect('/suppliers');
    }

    /**
     * Soft delete supplier
     */
    public function delete(int $id): void
    {
        $supplier = $this->supplierModel->findById($id);
        if (!$supplier) {
            setFlash('danger', 'Supplier not found.');
            redirect('/suppliers');
        }

        $this->supplierModel->delete($id);

        setFlash('success', 'Supplier deleted successfully.');
        redirect('/suppliers');
    }

    /**
     * Render view
     */
    private function render(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data);
        $content = __DIR__ . '/../../views/' . $view . '.php';
        require __DIR__ . '/../../views/layouts/' . $layout . '.php';
    }
}
