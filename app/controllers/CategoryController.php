<?php
/**
 * StockVision - Category Controller
 * Handles HTTP requests for Categories
 */

class CategoryController
{
    private Category $categoryModel;
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
        $this->categoryModel = new Category($this->db);
    }

    /**
     * List categories
     */
    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->categoryModel->getAll($page);

        $pageTitle = 'Categories';
        $categories = $result['data'];
        $pagination = [
            'current_page' => $result['current_page'],
            'total_pages'  => $result['pages'],
            'total'        => $result['total'],
            'base_url'     => '/categories',
        ];

        $this->render('categories/index', compact('pageTitle', 'categories', 'pagination'));
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $pageTitle = 'Add Category';
        $errors = getErrors();
        $this->render('categories/create', compact('pageTitle', 'errors'));
    }

    /**
     * Store new category
     */
    public function store(): void
    {
        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'color'       => trim($_POST['color'] ?? '#6366f1'),
        ];

        // Validate
        $errors = [];
        if ($err = validateRequired($data['name'], 'Name')) $errors['name'] = $err;
        if ($err = validateMaxLength($data['name'], 100, 'Name')) $errors['name'] = $err;
        if (empty($errors['name'])) {
            if ($err = validateUnique($this->db, 'categories', 'name', $data['name'])) {
                $errors['name'] = $err;
            }
        }
        
        // Simple hex color validation
        if (!preg_match('/^#[a-f0-9]{6}$/i', $data['color'])) {
            $errors['color'] = 'Invalid color format.';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect('/categories/create');
        }

        $id = $this->categoryModel->create($data);

        clearOld();
        setFlash('success', 'Category created successfully.');
        redirect('/categories');
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            setFlash('danger', 'Category not found.');
            redirect('/categories');
        }

        $pageTitle = 'Edit Category';
        $errors = getErrors();
        $this->render('categories/edit', compact('pageTitle', 'category', 'errors'));
    }

    /**
     * Update category
     */
    public function update(int $id): void
    {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            setFlash('danger', 'Category not found.');
            redirect('/categories');
        }

        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'color'       => trim($_POST['color'] ?? '#6366f1'),
        ];

        // Validate
        $errors = [];
        if ($err = validateRequired($data['name'], 'Name')) $errors['name'] = $err;
        if (empty($errors['name'])) {
            if ($err = validateUnique($this->db, 'categories', 'name', $data['name'], $id)) {
                $errors['name'] = $err;
            }
        }
        if (!preg_match('/^#[a-f0-9]{6}$/i', $data['color'])) {
            $errors['color'] = 'Invalid color format.';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect("/categories/{$id}/edit");
        }

        $this->categoryModel->update($id, $data);

        clearOld();
        setFlash('success', 'Category updated successfully.');
        redirect('/categories');
    }

    /**
     * Soft delete category
     */
    public function delete(int $id): void
    {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            setFlash('danger', 'Category not found.');
            redirect('/categories');
        }

        $this->categoryModel->delete($id);

        setFlash('success', 'Category deleted successfully.');
        redirect('/categories');
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
