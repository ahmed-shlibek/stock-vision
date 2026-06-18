<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/products" class="btn btn-icon btn-outline" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="m-0">Product Details</h1>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/products/<?= $product['id'] ?>/edit" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square"></i> Edit Product
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Image Card -->
        <div class="card mb-4 text-center">
            <div class="card-body">
                <?php if ($product['image']): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded mb-4" style="max-height: 250px; object-fit: contain;">
                <?php else: ?>
                    <div class="bg-secondary-light rounded d-flex align-items-center justify-content-center mb-4 mx-auto" style="height: 200px; width: 100%;">
                        <i class="fa-solid fa-box text-secondary fa-4x"></i>
                    </div>
                <?php endif; ?>

                <h3 class="mb-1"><?= htmlspecialchars($product['name']) ?></h3>
                <p class="text-secondary mb-4">SKU: <?= htmlspecialchars($product['sku']) ?></p>
            </div>
        </div>

        <!-- Supplier Card -->
        <?php if ($product['supplier_id']): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Supplier Information</h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar avatar-md bg-primary-light text-primary rounded">
                        <?= strtoupper(substr($product['supplier_name'], 0, 1)) ?>
                    </div>
                    <div class="font-medium"><?= htmlspecialchars($product['supplier_name']) ?></div>
                </div>
                
                <?php if ($product['supplier_email']): ?>
                    <div class="text-sm mb-2"><i class="fa-regular fa-envelope text-secondary mr-2"></i> <?= htmlspecialchars($product['supplier_email']) ?></div>
                <?php endif; ?>
                <?php if ($product['supplier_phone']): ?>
                    <div class="text-sm"><i class="fa-solid fa-phone text-secondary mr-2"></i> <?= htmlspecialchars($product['supplier_phone']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-8">
        <!-- Overview Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card primary">
                    <div class="stat-card-icon">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-label">Unit Price</div>
                        <div class="stat-card-value"><?= formatCurrency($product['unit_price']) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $qty = $product['quantity'];
                    $min = $product['min_stock_level'];
                    $stockVariant = 'success';
                    if ($qty == 0) $stockVariant = 'danger';
                    elseif ($qty <= $min) $stockVariant = 'warning';
                ?>
                <div class="stat-card <?= $stockVariant ?>">
                    <div class="stat-card-icon">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-label">Current Stock</div>
                        <div class="stat-card-value"><?= htmlspecialchars($qty) ?> <span class="text-sm text-secondary font-normal"><?= htmlspecialchars($product['unit']) ?></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card info">
                    <div class="stat-card-icon">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-label">Category</div>
                        <div class="stat-card-value text-truncate" style="font-size: 1.25rem;">
                            <?php if ($product['category_name']): ?>
                                <?= htmlspecialchars($product['category_name']) ?>
                            <?php else: ?>
                                <span class="text-secondary font-normal">Uncategorized</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Product Details</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-secondary text-sm">Description</div>
                    <div class="col-md-9">
                        <?= nl2br(htmlspecialchars($product['description'] ?: 'No description provided.')) ?>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 text-secondary text-sm">Min Stock Level</div>
                    <div class="col-md-9">
                        <?= htmlspecialchars($product['min_stock_level']) ?> <?= htmlspecialchars($product['unit']) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Movements -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">Recent Movements</h3>
                <div class="d-flex gap-2">
                    <a href="<?= BASE_URL ?>/stock/in" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-arrow-down-to-line"></i> Stock In
                    </a>
                    <a href="<?= BASE_URL ?>/stock/out" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Stock Out
                    </a>
                </div>
            </div>
            <?php if (empty($recentMovements)): ?>
                <div class="card-body text-center p-5 text-secondary">
                    <i class="fa-solid fa-clock-rotate-left fa-3x mb-3 text-secondary-light"></i>
                    <p>No movements recorded yet for this product.</p>
                </div>
            <?php else: ?>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-right">Quantity</th>
                                    <th class="text-right">Balance</th>
                                    <th>User</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentMovements as $mov): ?>
                                    <tr>
                                        <td class="text-secondary whitespace-nowrap">
                                            <?= date('M j, Y H:i', strtotime($mov['created_at'])) ?>
                                        </td>
                                        <td>
                                            <?php if ($mov['type'] === 'in'): ?>
                                                <span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> IN</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning"><i class="fa-solid fa-arrow-up"></i> OUT</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right font-medium <?= $mov['type'] === 'in' ? 'text-success' : 'text-warning' ?>">
                                            <?= $mov['type'] === 'in' ? '+' : '-' ?><?= htmlspecialchars($mov['quantity']) ?>
                                            <span class="text-xs text-secondary ml-1"><?= htmlspecialchars($mov['product_unit']) ?></span>
                                        </td>
                                        <td class="text-right">
                                            <div class="text-xs text-secondary">
                                                <?= htmlspecialchars($mov['quantity_before']) ?> &rarr; <span class="font-medium text-dark"><?= htmlspecialchars($mov['quantity_after']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-sm">
                                            <i class="fa-regular fa-user text-secondary mr-1"></i>
                                            <?= htmlspecialchars($mov['user_name']) ?>
                                        </td>
                                        <td class="text-sm text-secondary text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($mov['notes'] ?? '') ?>">
                                            <?= htmlspecialchars($mov['notes'] ?: '-') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="<?= BASE_URL ?>/stock?product_id=<?= $product['id'] ?>" class="btn btn-sm btn-outline">
                        View All Movements <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/js/products.js"></script>
