<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="text-secondary mt-1">Visual insights into your inventory performance</p>
    </div>
</div>

<!-- KPI Row -->
<div id="analytics-kpi" class="dashboard-stats-grid mb-6">
    <div class="stat-card primary">
        <div class="stat-card-icon"><i class="fa-solid fa-dollar-sign"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value" id="kpi-value">—</div>
            <div class="stat-card-label">Total Inventory Value</div>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-icon"><i class="fa-solid fa-box"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value" id="kpi-products">—</div>
            <div class="stat-card-label">Active Products</div>
        </div>
    </div>
    <div class="stat-card info">
        <div class="stat-card-icon"><i class="fa-solid fa-cubes"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value" id="kpi-units">—</div>
            <div class="stat-card-label">Total Units in Stock</div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="analytics-grid-2 mb-6">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-line text-primary mr-2"></i>Stock Movement Trend (30 Days)</h3>
        </div>
        <div class="card-body">
            <canvas id="chart-trend" height="120"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-pie text-secondary mr-2"></i>Inventory Value by Category</h3>
        </div>
        <div class="card-body" style="display:flex; align-items:center; justify-content:center;">
            <canvas id="chart-category" height="220" style="max-width:320px;"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="analytics-grid-2">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-column text-success mr-2"></i>Monthly Movement Comparison</h3>
        </div>
        <div class="card-body">
            <canvas id="chart-monthly" height="120"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-ranking-star text-warning mr-2"></i>Top Moving Products (30 Days)</h3>
        </div>
        <div class="card-body">
            <canvas id="chart-top-moving" height="200"></canvas>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(async function () {
    // Detect dark mode
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)';
    const tickColor = isDark ? '#94a3b8' : '#64748b';
    const textColor = isDark ? '#f1f5f9' : '#0f172a';

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = tickColor;

    // ── KPI ────────────────────────────────────────────────────
    try {
        const kpi = await fetch('<?= BASE_URL ?>/api/analytics/value').then(r => r.json());
        document.getElementById('kpi-value').textContent = '$' + Number(kpi.total_value).toLocaleString('en', {minimumFractionDigits:2, maximumFractionDigits:2});
        document.getElementById('kpi-products').textContent = Number(kpi.total_products).toLocaleString();
        document.getElementById('kpi-units').textContent = Number(kpi.total_units).toLocaleString();
    } catch(e) {}

    // ── Trend Line Chart ───────────────────────────────────────
    try {
        const trend = await fetch('<?= BASE_URL ?>/api/analytics/stock-trend').then(r => r.json());
        new Chart(document.getElementById('chart-trend'), {
            type: 'line',
            data: {
                labels: trend.labels,
                datasets: [
                    {
                        label: 'Stock In',
                        data: trend.inData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Stock Out',
                        data: trend.outData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245,158,11,0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { grid: { color: gridColor }, ticks: { color: tickColor, maxTicksLimit: 10 } },
                    y: { grid: { color: gridColor }, ticks: { color: tickColor, stepSize: 1 }, beginAtZero: true }
                }
            }
        });
    } catch(e) {}

    // ── Doughnut Chart ─────────────────────────────────────────
    try {
        const cats = await fetch('<?= BASE_URL ?>/api/analytics/categories').then(r => r.json());
        const catLabels = cats.map(c => c.name);
        const catValues = cats.map(c => parseFloat(c.value));
        const catColors = cats.map(c => c.color);

        new Chart(document.getElementById('chart-category'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{ data: catValues, backgroundColor: catColors, borderWidth: 2, borderColor: isDark ? '#1e293b' : '#fff', hoverOffset: 8 }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 12, boxWidth: 12 } },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' $' + Number(ctx.raw).toLocaleString('en', {minimumFractionDigits:2})
                        }
                    }
                }
            }
        });
    } catch(e) {}

    // ── Monthly Bar Chart ──────────────────────────────────────
    try {
        const monthly = await fetch('<?= BASE_URL ?>/api/analytics/monthly').then(r => r.json());
        new Chart(document.getElementById('chart-monthly'), {
            type: 'bar',
            data: {
                labels: monthly.labels,
                datasets: [
                    { label: 'Stock In', data: monthly.inData, backgroundColor: 'rgba(16,185,129,0.8)', borderRadius: 6 },
                    { label: 'Stock Out', data: monthly.outData, backgroundColor: 'rgba(245,158,11,0.8)', borderRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: tickColor } },
                    y: { grid: { color: gridColor }, ticks: { color: tickColor }, beginAtZero: true }
                }
            }
        });
    } catch(e) {}

    // ── Top Moving Products ────────────────────────────────────
    try {
        const top = await fetch('<?= BASE_URL ?>/api/analytics/top-moving').then(r => r.json());
        const topLabels = top.map(p => p.name.length > 22 ? p.name.substring(0,22)+'…' : p.name);
        new Chart(document.getElementById('chart-top-moving'), {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [
                    { label: 'Units In', data: top.map(p => p.total_in), backgroundColor: 'rgba(16,185,129,0.8)', borderRadius: 4 },
                    { label: 'Units Out', data: top.map(p => p.total_out), backgroundColor: 'rgba(245,158,11,0.8)', borderRadius: 4 }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { grid: { color: gridColor }, ticks: { color: tickColor }, beginAtZero: true },
                    y: { grid: { display: false }, ticks: { color: tickColor } }
                }
            }
        });
    } catch(e) {}

})();
</script>
<?php $pageScripts = ob_get_clean(); ?>
