<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p class="text-secondary mt-1">Welcome back, <?= htmlspecialchars(currentUserName()) ?>!</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/reports" class="btn btn-outline">
            <i class="fa-solid fa-file-export"></i> Reports
        </a>
        <a href="<?= BASE_URL ?>/products/create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Product
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="dashboard-stats-grid">
    <div class="stat-card primary">
        <div class="stat-card-icon"><i class="fa-solid fa-box"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['total_products'] ?? 0) ?></div>
            <div class="stat-card-label">Active Products</div>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-icon"><i class="fa-solid fa-cubes"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['total_quantity'] ?? 0) ?></div>
            <div class="stat-card-label">Total Units in Stock</div>
        </div>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-icon"><i class="fa-solid fa-dollar-sign"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value">$<?= number_format($stats['total_value'] ?? 0, 0) ?></div>
            <div class="stat-card-label">Inventory Value</div>
        </div>
    </div>
    <div class="stat-card info">
        <div class="stat-card-icon"><i class="fa-solid fa-truck"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['total_suppliers'] ?? 0) ?></div>
            <div class="stat-card-label">Active Suppliers</div>
        </div>
    </div>
    <div class="stat-card secondary">
        <div class="stat-card-icon"><i class="fa-solid fa-tags"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['total_categories'] ?? 0) ?></div>
            <div class="stat-card-label">Categories</div>
        </div>
    </div>
    <div class="stat-card danger">
        <div class="stat-card-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['low_stock_count'] ?? 0) ?></div>
            <div class="stat-card-label">Low Stock Alerts</div>
        </div>
        <?php if (($stats['low_stock_count'] ?? 0) > 0): ?>
            <a href="<?= BASE_URL ?>/alerts" class="stat-card-link">View Alerts →</a>
        <?php endif; ?>
    </div>
</div>

<!-- Main Widgets Row -->
<div class="dashboard-widgets-grid mt-6">

    <!-- Left: Trend Chart + Recent Movements -->
    <div class="d-flex flex-column gap-6">

        <!-- Stock Trend Chart -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-chart-line text-primary mr-2"></i>Stock Trend (Last 14 Days)</h3>
                <a href="<?= BASE_URL ?>/analytics" class="btn btn-sm btn-ghost">Full Analytics</a>
            </div>
            <div class="card-body" style="padding-top: 0;">
                <canvas id="dash-trend-chart" height="80"></canvas>
            </div>
        </div>

        <!-- Recent Movements -->
        <div class="card">
            <div class="card-header">
                <h3>Recent Stock Movements</h3>
                <a href="<?= BASE_URL ?>/stock" class="btn btn-sm btn-ghost">View All</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentMovements)): ?>
                    <div class="empty-state p-6">
                        <p class="text-secondary">No stock movements recorded yet.</p>
                        <a href="<?= BASE_URL ?>/stock/in" class="btn btn-sm btn-primary mt-3">Record First Movement</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th class="text-right">Qty</th>
                                    <th>Date</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentMovements as $m): ?>
                                    <tr>
                                        <td>
                                            <div class="font-medium"><?= htmlspecialchars($m['product_name']) ?></div>
                                            <div class="text-xs text-secondary"><?= htmlspecialchars($m['sku']) ?></div>
                                        </td>
                                        <td>
                                            <?php if ($m['type'] === 'in'): ?>
                                                <span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> IN</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning"><i class="fa-solid fa-arrow-up"></i> OUT</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right font-medium <?= $m['type'] === 'in' ? 'text-success' : 'text-warning' ?>">
                                            <?= $m['type'] === 'in' ? '+' : '-' ?><?= number_format($m['quantity']) ?>
                                        </td>
                                        <td class="text-sm text-secondary"><?= date('M j, H:i', strtotime($m['created_at'])) ?></td>
                                        <td class="text-sm"><?= htmlspecialchars(explode(' ', $m['user_name'])[0]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="d-flex flex-column gap-6">

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header"><h3>Quick Actions</h3></div>
            <div class="card-body">
                <div class="quick-actions-grid">
                    <a href="<?= BASE_URL ?>/stock/in" class="action-btn">
                        <i class="fa-solid fa-boxes-packing text-success"></i>
                        <span>Stock In</span>
                    </a>
                    <a href="<?= BASE_URL ?>/stock/out" class="action-btn">
                        <i class="fa-solid fa-truck-fast text-warning"></i>
                        <span>Stock Out</span>
                    </a>
                    <a href="<?= BASE_URL ?>/products/create" class="action-btn">
                        <i class="fa-solid fa-box-open text-primary"></i>
                        <span>New Product</span>
                    </a>
                    <a href="<?= BASE_URL ?>/alerts" class="action-btn">
                        <i class="fa-solid fa-bell text-danger"></i>
                        <span>Alerts <?php if (($stats['low_stock_count'] ?? 0) > 0): ?><span class="badge badge-danger ml-1"><?= $stats['low_stock_count'] ?></span><?php endif; ?></span>
                    </a>
                    <a href="<?= BASE_URL ?>/analytics" class="action-btn">
                        <i class="fa-solid fa-chart-pie text-secondary"></i>
                        <span>Analytics</span>
                    </a>
                    <a href="<?= BASE_URL ?>/reports" class="action-btn">
                        <i class="fa-solid fa-file-lines text-info"></i>
                        <span>Reports</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Low Stock Widget -->
        <?php if (!empty($lowStockItems)): ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-triangle-exclamation text-warning mr-2"></i>Low Stock</h3>
                <a href="<?= BASE_URL ?>/alerts" class="btn btn-sm btn-ghost">View All</a>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($lowStockItems as $item): ?>
                        <?php
                            $pct = $item['min_stock_level'] > 0
                                ? min(100, round(($item['quantity'] / $item['min_stock_level']) * 100))
                                : 0;
                            $barColor = $item['quantity'] === 0 ? 'var(--danger)' : ($pct <= 40 ? 'var(--warning)' : 'var(--success)');
                        ?>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <a href="<?= BASE_URL ?>/products/<?= $item['id'] ?>" class="text-sm font-medium text-dark text-decoration-none">
                                    <?= htmlspecialchars($item['name']) ?>
                                </a>
                                <span class="text-xs <?= $item['quantity'] === 0 ? 'text-danger font-bold' : 'text-warning font-medium' ?>">
                                    <?= $item['quantity'] ?>/<?= $item['min_stock_level'] ?> <?= htmlspecialchars($item['unit']) ?>
                                </span>
                            </div>
                            <div style="height:5px; background:var(--border-color); border-radius:99px; overflow:hidden;">
                                <div style="height:100%; width:<?= $pct ?>%; background:<?= $barColor ?>; border-radius:99px; transition:width 0.6s ease;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= BASE_URL ?>/stock/in" class="btn btn-sm btn-outline w-100 mt-4">
                    <i class="fa-solid fa-arrow-down-to-line"></i> Record Stock In
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Category Donut Chart -->
        <?php if (!empty($categoryData['labels'])): ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-chart-pie text-secondary mr-2"></i>Value by Category</h3>
            </div>
            <div class="card-body" style="display:flex; align-items:center; justify-content:center;">
                <canvas id="dash-cat-chart" height="200" style="max-width:240px;"></canvas>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function() {
    const isDark    = document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)';
    const tickColor = isDark ? '#94a3b8' : '#64748b';
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = tickColor;

    // ── Trend Line Chart ─────────────────────────────────
    const trendData = <?= json_encode($trendData) ?>;
    new Chart(document.getElementById('dash-trend-chart'), {
        type: 'line',
        data: {
            labels: trendData.labels,
            datasets: [
                {
                    label: 'Stock In',
                    data: trendData.inData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                },
                {
                    label: 'Stock Out',
                    data: trendData.outData,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12 } } },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: tickColor, maxTicksLimit: 7 } },
                y: { grid: { color: gridColor }, ticks: { color: tickColor, stepSize: 1 }, beginAtZero: true }
            }
        }
    });

    // ── Category Donut ───────────────────────────────────
    const catEl = document.getElementById('dash-cat-chart');
    if (catEl) {
        const catData = <?= json_encode($categoryData) ?>;
        new Chart(catEl, {
            type: 'doughnut',
            data: {
                labels: catData.labels,
                datasets: [{
                    data: catData.values,
                    backgroundColor: catData.colors,
                    borderWidth: 2,
                    borderColor: isDark ? '#1e293b' : '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 10, boxWidth: 12, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' $' + Number(ctx.raw).toLocaleString('en', {minimumFractionDigits:2})
                        }
                    }
                }
            }
        });
    }
})();
</script>
<?php $pageScripts = ob_get_clean(); ?>
