<header class="topbar">
    <div class="topbar-left">
        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Open Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></div>
    </div>

    <div class="topbar-center">
        <!-- Advanced Search Placeholder (Phase 10) -->
        <div class="search-bar">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="global-search" placeholder="Search products, SKUs, or barcodes... (Ctrl+K)">
        </div>
    </div>

    <div class="topbar-right">
        <!-- Theme Toggle -->
        <button class="topbar-btn" id="theme-toggle" aria-label="Toggle Dark Mode" data-tooltip="Theme">
            <i class="fa-solid fa-moon"></i>
        </button>

        <!-- Alerts -->
        <a href="<?= BASE_URL ?>/alerts" class="topbar-btn" aria-label="View Alerts" data-tooltip="Alerts">
            <i class="fa-solid fa-bell"></i>
            <span class="alert-count d-none" id="topbar-alert-badge">0</span>
        </a>

        <!-- User Dropdown -->
        <div class="dropdown" id="user-dropdown">
            <div class="topbar-user" id="user-dropdown-toggle">
                <div class="topbar-user-info">
                    <div class="topbar-user-name"><?= htmlspecialchars(currentUserName() ?? '') ?></div>
                    <div class="topbar-user-role"><?= htmlspecialchars(currentUserRole() ?? '') ?></div>
                </div>
                <?php $avatar = currentUserAvatar(); ?>
                <?php if ($avatar): ?>
                    <div class="avatar avatar-sm">
                        <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($avatar) ?>" alt="Avatar">
                    </div>
                <?php else: ?>
                    <div class="avatar avatar-sm">
                        <?= strtoupper(substr(currentUserName() ?? 'U', 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="dropdown-menu" id="user-dropdown-menu">
                <a href="<?= BASE_URL ?>/profile" class="dropdown-item">
                    <i class="fa-solid fa-user"></i> My Profile
                </a>
                <a href="<?= BASE_URL ?>/change-password" class="dropdown-item">
                    <i class="fa-solid fa-key"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>
                <form action="<?= BASE_URL ?>/logout" method="POST" class="m-0">
                    <?= csrfField() ?>
                    <button type="submit" class="dropdown-item danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
