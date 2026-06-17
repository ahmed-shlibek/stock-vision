<?php
/**
 * StockVision - User Controller
 * User management CRUD
 */

class UserController
{
    private User $userModel;
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
        $this->userModel = new User($this->db);
    }

    /**
     * List all users (paginated)
     */
    public function index(): void
    {
        requireLogin();

        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->userModel->getAll($page);

        $pageTitle = 'User Management';
        $users = $result['data'];
        $pagination = [
            'current_page' => $result['current_page'],
            'total_pages'  => $result['pages'],
            'total'        => $result['total'],
            'base_url'     => '/users',
        ];
        $errors = getErrors();

        $this->render('users/index', compact('pageTitle', 'users', 'pagination', 'errors'));
    }

    /**
     * Show create user form
     */
    public function create(): void
    {
        requireLogin();

        $pageTitle = 'Create User';
        $errors = getErrors();
        $this->render('users/create', compact('pageTitle', 'errors'));
    }

    /**
     * Store a new user
     */
    public function store(): void
    {
        requireLogin();

        $data = [
            'name'     => trim($_POST['name'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
        ];
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validate
        $errors = [];
        if ($err = validateRequired($data['name'], 'Name')) $errors['name'] = $err;
        if ($err = validateMaxLength($data['name'], 100, 'Name')) $errors['name'] = $err;
        if ($err = validateRequired($data['email'], 'Email')) $errors['email'] = $err;
        if (empty($errors['email']) && ($err = validateEmail($data['email']))) $errors['email'] = $err;
        if (empty($errors['email'])) {
            if ($err = validateUnique($this->db, 'users', 'email', $data['email'])) {
                $errors['email'] = $err;
            }
        }
        if ($err = validateRequired($data['password'], 'Password')) $errors['password'] = $err;
        if (empty($errors['password']) && ($err = validateMinLength($data['password'], 8, 'Password'))) {
            $errors['password'] = $err;
        }
        if ($data['password'] !== $passwordConfirm) {
            $errors['password_confirm'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect('/users/create');
        }

        $userId = $this->userModel->create($data);
        clearOld();
        setFlash('success', 'User created successfully.');
        redirect('/users');
    }

    /**
     * Show edit user form
     */
    public function edit(int $id): void
    {
        requireLogin();

        $user = $this->userModel->findById($id);
        if (!$user) {
            setFlash('danger', 'User not found.');
            redirect('/users');
        }

        $pageTitle = 'Edit User';
        $errors = getErrors();
        $this->render('users/edit', compact('pageTitle', 'user', 'errors'));
    }

    /**
     * Update an existing user
     */
    public function update(int $id): void
    {
        requireLogin();

        $user = $this->userModel->findById($id);
        if (!$user) {
            setFlash('danger', 'User not found.');
            redirect('/users');
        }

        $data = [
            'name'     => trim($_POST['name'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
        ];

        // Validate
        $errors = [];
        if ($err = validateRequired($data['name'], 'Name')) $errors['name'] = $err;
        if ($err = validateRequired($data['email'], 'Email')) $errors['email'] = $err;
        if (empty($errors['email']) && ($err = validateEmail($data['email']))) $errors['email'] = $err;
        if (empty($errors['email'])) {
            if ($err = validateUnique($this->db, 'users', 'email', $data['email'], $id)) {
                $errors['email'] = $err;
            }
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect("/users/{$id}/edit");
        }

        $this->userModel->update($id, $data);
        clearOld();
        setFlash('success', 'User updated successfully.');
        redirect('/users');
    }

    /**
     * Delete a user
     */
    public function delete(int $id): void
    {
        requireLogin();

        // Prevent self-deletion
        if ($id === currentUserId()) {
            setFlash('danger', 'You cannot delete your own account.');
            redirect('/users');
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            setFlash('danger', 'User not found.');
            redirect('/users');
        }

        $this->userModel->delete($id);
        setFlash('success', 'User deleted successfully.');
        redirect('/users');
    }

    /**
     * Render a view with layout
     */
    private function render(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data);
        $content = __DIR__ . '/../../views/' . $view . '.php';
        require __DIR__ . '/../../views/layouts/' . $layout . '.php';
    }
}
