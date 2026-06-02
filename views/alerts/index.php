<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="text-secondary mt-1">Products that need immediate restocking</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/stock/in" class="btn btn-success">
            <i class="fa-solid fa-arrow-down-to-line"></i> Record Stock In
        </a>
    </div>
</div>

<?php if (empty($outOfStock) && empty($lowStock)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-circle-check empty-state-icon text-success"></i>
            <h3>All Stock Levels Healthy!</h3>
            <p>No products are currently low or out of stock. Great job keeping the inventory well stocked.</p>
        </div>
    </div>
<?php else: ?>

<?php if (!empty($outOfStock)): ?>
<div class="mb-6">
    <div class="d-flex align-items-center gap-3 mb-3">
        <span class="badge badge-danger" style="font-size: 0.9rem; padding: 6px 12px;">
            <i class="fa-solid fa-circle-xmark"></i> Out of Stock — <?= count($outOfStock) ?> product<?= count($outOfStock) !== 1 ? 's' : '' ?>
        </span>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th class="text-right">Min. Level</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($outOfStock as $p): ?>
                            <tr>
                                <td>
                                    <div class="font-medium"><?= htmlspecialchars($p['name']) ?></div>
                                    <div class="text-xs text-secondary">SKU: <?= htmlspecialchars($p['sku']) ?></div>
                                </td>
                                <td>
                                    <?php if ($p['category_name']): ?>
                                        <span class="badge" style="background-color:<?= htmlspecialchars($p['category_color']) ?>20;color:<?= htmlspecialchars($p['category_color']) ?>;border-color:<?= htmlspecialchars($p['category_color']) ?>50">
                                            <?= htmlspecialchars($p['category_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-secondary">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($p['supplier_name']): ?>
                                        <div><?= htmlspecialchars($p['supplier_name']) ?></div>
                                        <?php if ($p['supplier_email']): ?>
                                            <div class="text-xs text-secondary"><?= htmlspecialchars($p['supplier_email']) ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-secondary">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right font-medium"><?= $p['min_stock_level'] ?> <?= htmlspecialchars($p['unit']) ?></td>
                                <td class="text-right">
                                    <div class="actions justify-content-end">
                                        <a href="<?= BASE_URL ?>/stock/in?product=<?= $p['id'] ?>" class="btn btn-sm btn-success" title="Add Stock">
                                            <i class="fa-solid fa-plus"></i> Restock
                                        </a>
                                        <a href="<?= BASE_URL ?>/products/<?= $p['id'] ?>" class="btn btn-sm btn-icon btn-ghost" title="View Product">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($lowStock)): ?>
<div>
    <div class="d-flex align-items-center gap-3 mb-3">
        <span class="badge badge-warning" style="font-size: 0.9rem; padding: 6px 12px;">
            <i class="fa-solid fa-triangle-exclamation"></i> Low Stock — <?= count($lowStock) ?> product<?= count($lowStock) !== 1 ? 's' : '' ?>
        </span>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Stock Level</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStock as $p): ?>
                            <?php
                                $pct = $p['min_stock_level'] > 0
                                    ? min(100, round(($p['quantity'] / $p['min_stock_level']) * 100))
                                    : 100;
                            ?>
                            <tr>
                                <td>
                                    <div class="font-medium"><?= htmlspecialchars($p['name']) ?></div>
                                    <div class="text-xs text-secondary">SKU: <?= htmlspecialchars($p['sku']) ?></div>
                                </td>
                                <td>
                                    <?php if ($p['category_name']): ?>
                                        <span class="badge" style="background-color:<?= htmlspecialchars($p['category_color']) ?>20;color:<?= htmlspecialchars($p['category_color']) ?>;border-color:<?= htmlspecialchars($p['category_color']) ?>50">
                                            <?= htmlspecialchars($p['category_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-secondary">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-sm">
                                    <?= htmlspecialchars($p['supplier_name'] ?: '—') ?>
                                </td>
                                <td style="min-width: 180px;">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="font-medium text-warning"><?= $p['quantity'] ?></span>
                                        <span class="text-secondary text-xs">/ <?= $p['min_stock_level'] ?> min (<?= $p['unit'] ?>)</span>
                                    </div>
                                    <div class="progress-bar-wrap" style="height: 6px; background: var(--warning-light); border-radius: 99px; overflow: hidden;">
                                        <div style="height:100%; width: <?= $pct ?>%; background: var(--warning); border-radius: 99px; transition: width 0.6s ease;"></div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="actions justify-content-end">
                                        <a href="<?= BASE_URL ?>/stock/in?product=<?= $p['id'] ?>" class="btn btn-sm btn-warning" title="Add Stock">
                                            <i class="fa-solid fa-plus"></i> Restock
                                        </a>
                                        <a href="<?= BASE_URL ?>/products/<?= $p['id'] ?>" class="btn btn-sm btn-icon btn-ghost" title="View Product">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
