<?php
/**
 * StockVision - Supplier Model
 * Handles database operations for suppliers (with soft deletes)
 */

class Supplier
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get paginated list of active suppliers
     */
    public function getAll(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;

        // Count total
        $stmt = $this->db->query("SELECT COUNT(*) FROM `suppliers` WHERE `deleted_at` IS NULL");
        $total = $stmt->fetchColumn();

        // Fetch data
        $stmt = $this->db->prepare("
            SELECT * FROM `suppliers` 
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
     * Get all active suppliers without pagination (for dropdowns)
     */
    public function getAllList(): array
    {
        $stmt = $this->db->query("
            SELECT `id`, `name` 
            FROM `suppliers` 
            WHERE `deleted_at` IS NULL AND `is_active` = 1
            ORDER BY `name` ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Find supplier by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM `suppliers` 
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        $stmt->execute([$id]);
        $supplier = $stmt->fetch();
        return $supplier ?: null;
    }

    /**
     * Create a new supplier
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO `suppliers` (`name`, `phone`, `email`, `address`, `is_active`) 
            VALUES (:name, :phone, :email, :address, :is_active)
        ");
        
        $stmt->execute([
            ':name'      => $data['name'],
            ':phone'     => $data['phone'] ?? null,
            ':email'     => $data['email'] ?? null,
            ':address'   => $data['address'] ?? null,
            ':is_active' => $data['is_active'] ?? 1
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Update existing supplier
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE `suppliers` 
            SET `name` = :name, 
                `phone` = :phone, 
                `email` = :email,
                `address` = :address,
                `is_active` = :is_active
            WHERE `id` = :id AND `deleted_at` IS NULL
        ");
        
        return $stmt->execute([
            ':id'        => $id,
            ':name'      => $data['name'],
            ':phone'     => $data['phone'] ?? null,
            ':email'     => $data['email'] ?? null,
            ':address'   => $data['address'] ?? null,
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }

    /**
     * Soft delete supplier
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE `suppliers` 
            SET `deleted_at` = NOW() 
            WHERE `id` = ?
        ");
        return $stmt->execute([$id]);
    }
}
