<?php
/**
 * Expected variables:
 * $pagination = [
 *     'current_page' => int,
 *     'total_pages'  => int,
 *     'total'        => int,
 *     'per_page'     => int,   (optional)
 *     'base_url'     => string
 * ]
 */
if (!isset($pagination) || $pagination['total_pages'] <= 1) {
    return;
}

$current = $pagination['current_page'];
$total = $pagination['total_pages'];
$baseUrl = BASE_URL . $pagination['base_url'];
$query = $_GET;

// Helper to generate URL
$pageUrl = function($page) use ($baseUrl, $query) {
    $query['page'] = $page;
    return $baseUrl . '?' . http_build_query($query);
};

// Range info
$perPage = $pagination['per_page'] ?? null;
if ($perPage) {
    $from = ($current - 1) * $perPage + 1;
    $to   = min($current * $perPage, $pagination['total']);
    $rangeText = 'Showing ' . number_format($from) . '–' . number_format($to) . ' of ' . number_format($pagination['total']) . ' records';
} else {
    $rangeText = 'Showing ' . number_format($pagination['total']) . ' records';
}

// Window of pages to show
$window = 2;
$start = max(1, $current - $window);
$end = min($total, $current + $window);
?>

<div class="pagination-wrapper">
    <div class="text-sm text-secondary">
        <?= $rangeText ?>
    </div>

    <div class="pagination">
        <!-- Previous -->
        <?php if ($current > 1): ?>
            <a href="<?= $pageUrl($current - 1) ?>" class="pagination-btn" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
                <span>Prev</span>
            </a>
        <?php else: ?>
            <span class="pagination-btn disabled" aria-disabled="true">
                <i class="fa-solid fa-chevron-left"></i>
                <span>Prev</span>
            </span>
        <?php endif; ?>

        <!-- First page -->
        <?php if ($start > 1): ?>
            <a href="<?= $pageUrl(1) ?>" class="pagination-page">1</a>
            <?php if ($start > 2): ?>
                <span class="pagination-ellipsis">…</span>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Page numbers -->
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i === $current): ?>
                <span class="pagination-page active"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= $pageUrl($i) ?>" class="pagination-page"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <!-- Last page -->
        <?php if ($end < $total): ?>
            <?php if ($end < $total - 1): ?>
                <span class="pagination-ellipsis">…</span>
            <?php endif; ?>
            <a href="<?= $pageUrl($total) ?>" class="pagination-page"><?= $total ?></a>
        <?php endif; ?>

        <!-- Next -->
        <?php if ($current < $total): ?>
            <a href="<?= $pageUrl($current + 1) ?>" class="pagination-btn" aria-label="Next">
                <span>Next</span>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        <?php else: ?>
            <span class="pagination-btn disabled" aria-disabled="true">
                <span>Next</span>
                <i class="fa-solid fa-chevron-right"></i>
            </span>
        <?php endif; ?>
    </div>
</div>
