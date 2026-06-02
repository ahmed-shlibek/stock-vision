<?php
/**
 * StockVision - Category Model
 * Handles database operations for categories (with soft deletes)
 */

class Category
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get paginated list of active categories
     */
    public function getAll(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;

        // Count total
        $stmt = $this->db->query("SELECT COUNT(*) FROM `categories` WHERE `deleted_at` IS NULL");
        $total = $stmt->fetchColumn();

        // Fetch data
        $stmt = $this->db->prepare("
            SELECT * FROM `categories` 
            WHERE `deleted_at` IS NULL 
            ORDER BY `name` ASC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'current_page' => $page,
            'pages'        => ceil($total / $perPage)
        ];
    }

    /**
     * Get all active categories without pagination (for dropdowns)
     */
    public function getAllList(): array
    {
        $stmt = $this->db->query("
            SELECT `id`, `name`, `color` 
            FROM `categories` 
            WHERE `deleted_at` IS NULL 
            ORDER BY `name` ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Find category by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM `categories` 
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        $stmt->execute([$id]);
        $category = $stmt->fetch();
        return $category ?: null;
    }

    /**
     * Create a new category
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO `categories` (`name`, `description`, `color`) 
            VALUES (:name, :description, :color)
        ");
        
        $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':color'       => $data['color'] ?? '#6366f1'
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Update existing category
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE `categories` 
            SET `name` = :name, 
                `description` = :description, 
                `color` = :color 
            WHERE `id` = :id AND `deleted_at` IS NULL
        ");
        
        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':color'       => $data['color'] ?? '#6366f1'
        ]);
    }

    /**
     * Soft delete category
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE `categories` 
            SET `deleted_at` = NOW() 
            WHERE `id` = ?
        ");
        return $stmt->execute([$id]);
    }
}
