<div class="page-header">
    <h1><?= htmlspecialchars($pageTitle) ?></h1>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/products/create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Product
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/products" method="GET" class="d-flex flex-wrap gap-3 align-items-end">
            <div class="form-group mb-0 flex-grow-1" style="min-width: 250px;">
                <label for="search" class="form-label">Search Products</label>
                <div class="input-icon-wrapper icon-left">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="search" name="search" class="form-control" 
                           placeholder="Search by name or SKU..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
            </div>
            
            <div class="form-group mb-0" style="min-width: 200px;">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-control">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Filter</button>
            <?php if ($search || $categoryId): ?>
                <a href="<?= BASE_URL ?>/products" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($products)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-box-open empty-state-icon"></i>
                <h3>No Products Found</h3>
                <p>Try adjusting your search filters or create a new product.</p>
                <a href="<?= BASE_URL ?>/products/create" class="btn btn-primary mt-4">Add Product</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Stock</th>
                            <th class="text-right">Status</th>
                            <th width="120" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $prod): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if ($prod['image']): ?>
                                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="avatar avatar-md rounded">
                                        <?php else: ?>
                                            <div class="avatar avatar-md bg-secondary-light text-secondary rounded">
                                                <i class="fa-solid fa-box"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="font-medium">
                                                <a href="<?= BASE_URL ?>/products/<?= $prod['id'] ?>" class="text-dark text-decoration-none">
                                                    <?= htmlspecialchars($prod['name']) ?>
                                                </a>
                                            </div>
                                            <div class="text-sm text-secondary">SKU: <?= htmlspecialchars($prod['sku']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($prod['category_name']): ?>
                                        <span class="badge" style="background-color: <?= htmlspecialchars($prod['category_color']) ?>20; color: <?= htmlspecialchars($prod['category_color']) ?>; border-color: <?= htmlspecialchars($prod['category_color']) ?>50;">
                                            <?= htmlspecialchars($prod['category_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right font-medium">
                                    <?= formatCurrency($prod['unit_price']) ?>
                                </td>
                                <td class="text-right">
                                    <?php
                                        $stockClass = '';
                                        if ($prod['quantity'] == 0) $stockClass = 'text-danger font-bold';
                                        elseif ($prod['quantity'] <= $prod['min_stock_level']) $stockClass = 'text-warning font-bold';
                                    ?>
                                    <span class="<?= $stockClass ?>">
                                        <?= htmlspecialchars($prod['quantity']) ?> <?= htmlspecialchars($prod['unit']) ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <?php if ($prod['quantity'] == 0): ?>
                                        <span class="badge badge-danger">Out of Stock</span>
                                    <?php elseif ($prod['quantity'] <= $prod['min_stock_level']): ?>
                                        <span class="badge badge-warning">Low Stock</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">In Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <div class="actions justify-content-end">
                                        <a href="<?= BASE_URL ?>/products/<?= $prod['id'] ?>" class="btn btn-icon btn-ghost btn-sm text-primary" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/products/<?= $prod['id'] ?>/edit" class="btn btn-icon btn-ghost btn-sm" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-icon btn-ghost btn-sm text-danger delete-btn" 
                                                data-id="<?= $prod['id'] ?>" 
                                                data-name="<?= htmlspecialchars($prod['name']) ?>"
                                                title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                        <form id="delete-form-<?= $prod['id'] ?>" action="<?= BASE_URL ?>/products/<?= $prod['id'] ?>/delete" method="POST" class="d-none">                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php require __DIR__ . '/../partials/pagination.php'; ?>
        <?php endif; ?>
    </div>
</div>

<?php ob_start(); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm(`Are you sure you want to delete the product "${this.dataset.name}"? This action cannot be undone.`)) {
                document.getElementById(`delete-form-${this.dataset.id}`).submit();
            }
        });
    });
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
