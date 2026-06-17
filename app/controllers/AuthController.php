<?php
/**
 * StockVision - Authentication Controller
 * Handles login, logout, profile, and password change
 */

class AuthController
{
    private User $userModel;
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
        $this->userModel = new User($this->db);
    }

    /**
     * Show login page
     */
    public function showLogin(): void
    {
        if (isLoggedIn()) {
            redirect('/');
        }

        $pageTitle = 'Login';
        $errors = getErrors();
        $this->render('auth/login', compact('pageTitle', 'errors'), 'auth');
    }

    /**
     * Process login
     */
    public function login(): void
    {
        if (isLoggedIn()) {
            redirect('/');
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate inputs
        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        }
        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect('/login');
        }

        // Find user
        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            setFlash('danger', 'Invalid email or password.');
            setOld($_POST);
            redirect('/login');
        }

        // Success
        setSession($user);
        $this->userModel->updateLastLogin($user['id']);
        clearOld();

        setFlash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
        redirect('/');
    }

    /**
     * Process logout
     */
    public function logout(): void
    {
        destroySession();
        // Start a new session for flash messages
        session_start();
        setFlash('success', 'You have been logged out successfully.');
        redirect('/login');
    }

    /**
     * Show profile page
     */
    public function profile(): void
    {
        requireLogin();

        $user = $this->userModel->findById(currentUserId());
        if (!$user) {
            destroySession();
            redirect('/login');
        }

        $pageTitle = 'My Profile';
        $errors = getErrors();
        $this->render('auth/profile', compact('pageTitle', 'user', 'errors'));
    }

    /**
     * Update profile
     */
    public function updateProfile(): void
    {
        requireLogin();

        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validate
        $errors = [];
        if ($err = validateRequired($name, 'Name')) $errors['name'] = $err;
        if ($err = validateRequired($email, 'Email')) $errors['email'] = $err;
        if (empty($errors['email']) && ($err = validateEmail($email))) $errors['email'] = $err;
        if (empty($errors['email'])) {
            if ($err = validateUnique($this->db, 'users', 'email', $email, currentUserId())) {
                $errors['email'] = $err;
            }
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($_POST);
            redirect('/profile');
        }

        // Update
        $data = ['name' => $name, 'email' => $email];
        $this->userModel->update(currentUserId(), $data);

        // Update session
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;

        clearOld();
        setFlash('success', 'Profile updated successfully.');
        redirect('/profile');
    }

    /**
     * Show change password page
     */
    public function showChangePassword(): void
    {
        requireLogin();
        $pageTitle = 'Change Password';
        $errors = getErrors();
        $this->render('auth/change-password', compact('pageTitle', 'errors'));
    }

    /**
     * Process password change
     */
    public function changePassword(): void
    {
        requireLogin();

        $current  = $_POST['current_password'] ?? '';
        $new      = $_POST['new_password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        // Validate
        $errors = [];
        if (empty($current)) $errors['current_password'] = 'Current password is required.';
        if (empty($new)) $errors['new_password'] = 'New password is required.';
        if ($new && ($err = validateMinLength($new, 8, 'New password'))) $errors['new_password'] = $err;
        if ($new !== $confirm) $errors['password_confirm'] = 'Passwords do not match.';

        // Verify current password
        if (empty($errors['current_password'])) {
            $user = $this->userModel->findById(currentUserId());
            if (!password_verify($current, $user['password'])) {
                $errors['current_password'] = 'Current password is incorrect.';
            }
        }

        if (!empty($errors)) {
            setErrors($errors);
            redirect('/change-password');
        }

        $this->userModel->changePassword(currentUserId(), $new);
        setFlash('success', 'Password changed successfully.');
        redirect('/profile');
    }

    /**
     * Render a view with a layout
     */
    private function render(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data);
        $content = __DIR__ . '/../../views/' . $view . '.php';
        require __DIR__ . '/../../views/layouts/' . $layout . '.php';
    }
}
