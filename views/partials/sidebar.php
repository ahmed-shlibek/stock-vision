<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Strip base URL prefix if needed
if (BASE_URL !== '' && str_starts_with($currentPath, BASE_URL)) {
    $currentPath = substr($currentPath, strlen(BASE_URL));
}

function isActive(string $path): string {
    global $currentPath;
    if ($path === '/' && $currentPath === '/') return 'active';
    if ($path !== '/' && str_starts_with($currentPath, $path)) return 'active';
    return '';
}
?>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <div class="sidebar-brand">StockVision</div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <div class="sidebar-section-label">Main</div>
            <a href="<?= BASE_URL ?>/" class="sidebar-link <?= isActive('/') ?>">
                <i class="fa-solid fa-gauge-high"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-label">Inventory</div>
            <a href="<?= BASE_URL ?>/products" class="sidebar-link <?= isActive('/products') ?>">
                <i class="fa-solid fa-box"></i>
                <span class="link-text">Products</span>
            </a>
            <a href="<?= BASE_URL ?>/categories" class="sidebar-link <?= isActive('/categories') ?>">
                <i class="fa-solid fa-tags"></i>
                <span class="link-text">Categories</span>
            </a>
            <a href="<?= BASE_URL ?>/suppliers" class="sidebar-link <?= isActive('/suppliers') ?>">
                <i class="fa-solid fa-truck"></i>
                <span class="link-text">Suppliers</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-label">Operations</div>
            <a href="<?= BASE_URL ?>/stock" class="sidebar-link <?= isActive('/stock') ?>">
                <i class="fa-solid fa-exchange-alt"></i>
                <span class="link-text">Stock Movements</span>
            </a>
            <a href="<?= BASE_URL ?>/alerts" class="sidebar-link <?= isActive('/alerts') ?>">
                <i class="fa-solid fa-bell"></i>
                <span class="link-text">Alerts</span>
                <span class="badge badge-danger d-none" id="sidebar-alert-badge">0</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-label">System</div>
            <a href="<?= BASE_URL ?>/users" class="sidebar-link <?= isActive('/users') ?>">
                <i class="fa-solid fa-users"></i>
                <span class="link-text">Users</span>
            </a>
        </div>
    </nav>

    <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle Sidebar">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
</aside>
