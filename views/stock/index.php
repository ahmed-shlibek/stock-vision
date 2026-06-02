<div class="page-header">
    <h1><?= htmlspecialchars($pageTitle) ?></h1>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/stock/in" class="btn btn-success">
            <i class="fa-solid fa-arrow-down-to-line"></i> Stock In
        </a>
        <a href="<?= BASE_URL ?>/stock/out" class="btn btn-warning">
            <i class="fa-solid fa-arrow-up-from-bracket"></i> Stock Out
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/stock" method="GET" class="d-flex flex-wrap gap-3 align-items-end">
            <div class="form-group mb-0" style="min-width: 150px;">
                <label for="type" class="form-label">Type</label>
                <select id="type" name="type" class="form-control">
                    <option value="">All Types</option>
                    <option value="in" <?= ($filters['type'] ?? '') === 'in' ? 'selected' : '' ?>>Stock In</option>
                    <option value="out" <?= ($filters['type'] ?? '') === 'out' ? 'selected' : '' ?>>Stock Out</option>
                </select>
            </div>
            
            <div class="form-group mb-0">
                <label for="date_from" class="form-label">Date From</label>
                <input type="date" id="date_from" name="date_from" class="form-control" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
            </div>

            <div class="form-group mb-0">
                <label for="date_to" class="form-label">Date To</label>
                <input type="date" id="date_to" name="date_to" class="form-control" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Filter</button>
            <?php if (!empty(array_filter($filters))): ?>
                <a href="<?= BASE_URL ?>/stock" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($movements)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-clock-rotate-left empty-state-icon"></i>
                <h3>No Stock Movements</h3>
                <p>There are no recorded stock operations matching your criteria.</p>
                <div class="mt-4 d-flex justify-content-center gap-3">
                    <a href="<?= BASE_URL ?>/stock/in" class="btn btn-success">Record Stock In</a>
                    <a href="<?= BASE_URL ?>/stock/out" class="btn btn-warning">Record Stock Out</a>
                </div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Balance</th>
                            <th>User</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movements as $mov): ?>
                            <tr>
                                <td class="text-secondary whitespace-nowrap">
                                    <?= date('M j, Y H:i', strtotime($mov['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="font-medium">
                                        <a href="<?= BASE_URL ?>/products/<?= $mov['product_id'] ?>" class="text-dark text-decoration-none">
                                            <?= htmlspecialchars($mov['product_name']) ?>
                                        </a>
                                    </div>
                                    <div class="text-xs text-secondary">SKU: <?= htmlspecialchars($mov['product_sku']) ?></div>
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
                                <td class="text-sm text-secondary text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($mov['notes']) ?>">
                                    <?= htmlspecialchars($mov['notes'] ?: '-') ?>
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
