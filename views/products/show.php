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
        <!-- Image & Barcode Card -->
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

                <?php if ($product['barcode']): ?>
                    <div class="barcode-container p-3 bg-white border rounded">
                        <svg id="barcode-svg" data-value="<?= htmlspecialchars($product['barcode']) ?>"></svg>
                    </div>
                <?php else: ?>
                    <div class="p-3 bg-secondary-light border rounded text-secondary text-sm">
                        No barcode assigned
                    </div>
                <?php endif; ?>
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
                <div class="stat-card">
                    <div class="stat-icon bg-primary-light text-primary">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Unit Price</div>
                        <div class="stat-value"><?= formatCurrency($product['unit_price']) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <?php 
                        $qty = $product['quantity'];
                        $min = $product['min_stock_level'];
                        $bgClass = 'bg-success-light text-success';
                        if ($qty == 0) $bgClass = 'bg-danger-light text-danger';
                        elseif ($qty <= $min) $bgClass = 'bg-warning-light text-warning';
                    ?>
                    <div class="stat-icon <?= $bgClass ?>">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Current Stock</div>
                        <div class="stat-value"><?= htmlspecialchars($qty) ?> <span class="text-sm text-secondary font-normal"><?= htmlspecialchars($product['unit']) ?></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon bg-info-light text-info">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Category</div>
                        <div class="stat-value text-truncate" style="font-size: 1.25rem;">
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
                    <div class="col-md-3 text-secondary text-sm">Status</div>
                    <div class="col-md-9">
                        <?php if ($product['is_active']): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Inactive</span>
                        <?php endif; ?>
                    </div>
                </div>
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

        <!-- Recent Movements (Placeholder for Phase 3) -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Movements</h3>
            </div>
            <div class="card-body text-center p-5 text-secondary">
                <i class="fa-solid fa-clock-rotate-left fa-3x mb-3 text-secondary-light"></i>
                <p>Movement history will be available in the next phase.</p>
            </div>
        </div>
    </div>
</div>

<!-- Load JsBarcode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="<?= BASE_URL ?>/js/products.js"></script>
