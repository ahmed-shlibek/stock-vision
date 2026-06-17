/**
 * StockVision - Main Application Script
 * Handles layout interactivity: sidebar, dropdowns
 */

document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initUserDropdown();
    initMobileMenu();
    initAlertBadge();

    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
});

function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    
    if (!sidebar || !toggleBtn) return;

    // Check localStorage for saved state
    const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
    }

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebar_collapsed', sidebar.classList.contains('collapsed'));
    });
}

function initUserDropdown() {
    const toggle = document.getElementById('user-dropdown-toggle');
    const menu = document.getElementById('user-dropdown-menu');
    
    if (!toggle || !menu) return;

    toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        menu.classList.toggle('active');
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!menu.contains(e.target) && !toggle.contains(e.target)) {
            menu.classList.remove('active');
        }
    });
}

function initMobileMenu() {
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    if (!mobileBtn || !sidebar || !overlay) return;

    function toggleMobileMenu() {
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
    }

    mobileBtn.addEventListener('click', toggleMobileMenu);
    overlay.addEventListener('click', toggleMobileMenu);
}

/**
 * Topbar alert badge — fetch the low/out-of-stock count and display it.
 */
function initAlertBadge() {
    const badge = document.getElementById('topbar-alert-badge');
    if (!badge) return;

    fetchAPI('/api/alerts/count')
        .then(data => {
            const count = data?.count ?? 0;
            badge.textContent = count;
            badge.classList.toggle('d-none', count === 0);
        })
        .catch(() => { /* badge stays hidden on failure */ });
}

