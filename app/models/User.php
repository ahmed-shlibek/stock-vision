<?php
/**
 * StockVision - User Model
 * Database operations for the users table
 */

class User
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? getDB();
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE `id` = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Get paginated list of all users
     */
    public function getAll(int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        // Count total
        $countStmt = $this->db->query("SELECT COUNT(*) FROM `users`");
        $total = (int)$countStmt->fetchColumn();
        $totalPages = max(1, (int)ceil($total / $perPage));

        // Clamp page number
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;

        // Fetch page
        $stmt = $this->db->prepare("
            SELECT `id`, `name`, `email`, `last_login`, `created_at`
            FROM `users`
            ORDER BY `created_at` DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$perPage, $offset]);

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'pages'        => $totalPages,
            'current_page' => $page,
        ];
    }

    /**
     * Create a new user
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO `users` (`name`, `email`, `password`)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Update user (not password)
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];

        foreach (['name', 'email'] as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "`{$field}` = ?";
                $values[] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $id;
        $sql = "UPDATE `users` SET " . implode(', ', $fields) . " WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Change user password
     */
    public function changePassword(int $id, string $newPassword): bool
    {
        $stmt = $this->db->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");
        return $stmt->execute([
            password_hash($newPassword, PASSWORD_BCRYPT),
            $id,
        ]);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE `users` SET `last_login` = NOW() WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Delete a user (hard delete)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `users` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Count all users
     */
    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
    }
}
