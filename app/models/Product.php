<?php
/**
 * StockVision - Product Model
 * Handles database operations for products including joins and search
 */

class Product
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get paginated products with search and filtering
     */
    public function getAll(int $page = 1, int $perPage = 10, string $search = '', ?int $categoryId = null): array
    {
        $offset = ($page - 1) * $perPage;
        
        $where = ["p.`deleted_at` IS NULL"];
        $params = [];

        if ($search !== '') {
            // Native prepared statements (EMULATE_PREPARES=false) require a distinct
            // placeholder per occurrence, so bind one per searched column.
            $where[] = "(p.`name` LIKE :search_name OR p.`sku` LIKE :search_sku)";
            $like = "%{$search}%";
            $params[':search_name'] = $like;
            $params[':search_sku']  = $like;
        }

        if ($categoryId) {
            $where[] = "p.`category_id` = :category_id";
            $params[':category_id'] = $categoryId;
        }

        $whereClause = implode(' AND ', $where);

        // Count total
        $countQuery = "SELECT COUNT(*) FROM `products` p WHERE {$whereClause}";
        $stmt = $this->db->prepare($countQuery);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        $total = $stmt->fetchColumn();

        // Fetch data
        $query = "
            SELECT p.*, 
                   c.`name` as category_name, c.`color` as category_color,
                   s.`name` as supplier_name 
            FROM `products` p
            LEFT JOIN `categories` c ON p.`category_id` = c.`id`
            LEFT JOIN `suppliers` s ON p.`supplier_id` = s.`id`
            WHERE {$whereClause}
            ORDER BY p.`name` ASC 
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
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
     * Find product by ID with relations
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   c.`name` as category_name, c.`color` as category_color,
                   s.`name` as supplier_name, s.`email` as supplier_email, s.`phone` as supplier_phone
            FROM `products` p
            LEFT JOIN `categories` c ON p.`category_id` = c.`id`
            LEFT JOIN `suppliers` s ON p.`supplier_id` = s.`id`
            WHERE p.`id` = ? AND p.`deleted_at` IS NULL
        ");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    /**
     * Create a new product
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO `products` (
                `name`, `sku`, `category_id`, `supplier_id`,
                `description`, `unit_price`, `quantity`, `min_stock_level`,
                `unit`, `image`
            ) VALUES (
                :name, :sku, :category_id, :supplier_id,
                :description, :unit_price, :quantity, :min_stock_level,
                :unit, :image
            )
        ");

        $stmt->execute([
            ':name'            => $data['name'],
            ':sku'             => $data['sku'],
            ':category_id'     => $data['category_id'] ?: null,
            ':supplier_id'     => $data['supplier_id'] ?: null,
            ':description'     => $data['description'] ?: null,
            ':unit_price'      => $data['unit_price'] ?? 0.00,
            ':quantity'        => $data['quantity'] ?? 0,
            ':min_stock_level' => $data['min_stock_level'] ?? 10,
            ':unit'            => $data['unit'] ?? 'piece',
            ':image'           => $data['image'] ?: null
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Update existing product
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE `products` SET 
                `name` = :name, 
                `sku` = :sku,
                `category_id` = :category_id,
                `supplier_id` = :supplier_id, 
                `description` = :description, 
                `unit_price` = :unit_price, 
                `min_stock_level` = :min_stock_level, 
                `unit` = :unit,
                `image` = :image
            WHERE `id` = :id AND `deleted_at` IS NULL
        ");

        return $stmt->execute([
            ':id'              => $id,
            ':name'            => $data['name'],
            ':sku'             => $data['sku'],
            ':category_id'     => $data['category_id'] ?: null,
            ':supplier_id'     => $data['supplier_id'] ?: null,
            ':description'     => $data['description'] ?: null,
            ':unit_price'      => $data['unit_price'] ?? 0.00,
            ':min_stock_level' => $data['min_stock_level'] ?? 10,
            ':unit'            => $data['unit'] ?? 'piece',
            ':image'           => $data['image'] ?: null
        ]);
    }

    /**
     * Update product stock quantity
     */
    public function updateQuantity(int $id, int $newQuantity): bool
    {
        $stmt = $this->db->prepare("
            UPDATE `products` 
            SET `quantity` = ? 
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        return $stmt->execute([$newQuantity, $id]);
    }

    /**
     * Soft delete product
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE `products` 
            SET `deleted_at` = NOW() 
            WHERE `id` = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Check if SKU exists
     */
    public function skuExists(string $sku, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM `products` WHERE `sku` = ? AND `deleted_at` IS NULL";
        $params = [$sku];
        
        if ($excludeId !== null) {
            $sql .= " AND `id` != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Get all products for dropdowns
     */
    public function getActiveProducts(): array
    {
        $stmt = $this->db->query("
            SELECT `id`, `name`, `sku`, `quantity`, `unit`
            FROM `products`
            WHERE `deleted_at` IS NULL
            ORDER BY `name` ASC
        ");
        return $stmt->fetchAll();
    }
}
