<header class="topbar">
    <div class="topbar-left">
        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Open Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></div>
    </div>

    <div class="topbar-right">
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
                    <div class="topbar-user-role"><?= htmlspecialchars(currentUserEmail() ?? '') ?></div>
                </div>
                <div class="avatar avatar-sm">
                    <?= strtoupper(substr(currentUserName() ?? 'U', 0, 1)) ?>
                </div>
            </div>
            
            <div class="dropdown-menu" id="user-dropdown-menu">
                <a href="<?= BASE_URL ?>/profile" class="dropdown-item">
                    <i class="fa-solid fa-user"></i> My Profile
                </a>
                <a href="<?= BASE_URL ?>/change-password" class="dropdown-item">
                    <i class="fa-solid fa-key"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>
                <form action="<?= BASE_URL ?>/logout" method="POST" class="m-0">                    <button type="submit" class="dropdown-item danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
