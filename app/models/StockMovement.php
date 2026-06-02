<?php
/**
 * StockVision - Stock Movement Model
 * Handles tracking inventory changes (In/Out) with database transactions
 */

class StockMovement
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get paginated stock movements with product and user details
     */
    public function getPaginated(int $page = 1, int $perPage = 15, array $filters = []): array
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $where = ["1=1"];

        if (!empty($filters['product_id'])) {
            $where[] = "sm.product_id = :product_id";
            $params[':product_id'] = $filters['product_id'];
        }

        if (!empty($filters['type'])) {
            $where[] = "sm.type = :type";
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = "DATE(sm.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = "DATE(sm.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }

        $whereClause = implode(' AND ', $where);

        // Count total records
        $countQuery = "SELECT COUNT(*) FROM stock_movements sm WHERE {$whereClause}";
        $stmt = $this->db->prepare($countQuery);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        $total = $stmt->fetchColumn();

        // Fetch records
        $query = "
            SELECT 
                sm.*, 
                p.name as product_name, 
                p.sku as product_sku,
                p.unit as product_unit,
                u.name as user_name
            FROM stock_movements sm
            JOIN products p ON sm.product_id = p.id
            JOIN users u ON sm.user_id = u.id
            WHERE {$whereClause}
            ORDER BY sm.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $records = $stmt->fetchAll();

        return [
            'data' => $records,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Record a stock movement transaction (IN or OUT)
     * Automatically updates the product's quantity
     * 
     * @throws Exception if transaction fails or insufficient stock
     */
    public function record(int $productId, int $userId, string $type, int $quantity, ?string $notes = null): bool
    {
        if ($quantity <= 0) {
            throw new Exception("Quantity must be greater than zero.");
        }

        if (!in_array($type, ['in', 'out'])) {
            throw new Exception("Invalid movement type. Must be 'in' or 'out'.");
        }

        try {
            $this->db->beginTransaction();

            // 1. Get current product quantity, locking the row for update
            $stmt = $this->db->prepare("SELECT quantity FROM products WHERE id = ? FOR UPDATE");
            $stmt->execute([$productId]);
            $currentQuantity = $stmt->fetchColumn();

            if ($currentQuantity === false) {
                throw new Exception("Product not found.");
            }

            $quantityBefore = (int) $currentQuantity;
            
            // 2. Calculate new quantity
            $quantityAfter = $quantityBefore;
            if ($type === 'in') {
                $quantityAfter += $quantity;
            } else { // out
                if ($quantity > $quantityBefore) {
                    throw new Exception("Insufficient stock for this operation. Current stock: " . $quantityBefore);
                }
                $quantityAfter -= $quantity;
            }

            // 3. Update product quantity
            $stmt = $this->db->prepare("UPDATE products SET quantity = ? WHERE id = ?");
            $stmt->execute([$quantityAfter, $productId]);

            // 4. Insert movement record
            $stmt = $this->db->prepare("
                INSERT INTO stock_movements (product_id, user_id, type, quantity, quantity_before, quantity_after, notes, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $productId,
                $userId,
                $type,
                $quantity,
                $quantityBefore,
                $quantityAfter,
                $notes
            ]);

            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}
