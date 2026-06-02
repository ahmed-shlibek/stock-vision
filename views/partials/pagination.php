<?php
/**
 * Expected variables:
 * $pagination = [
 *     'current_page' => int,
 *     'total_pages'  => int,
 *     'total'        => int,
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

// Window of pages to show
$window = 2;
$start = max(1, $current - $window);
$end = min($total, $current + $window);
?>

<div class="d-flex align-items-center justify-content-between mt-4">
    <div class="text-sm text-muted">
        Showing total <?= number_format($pagination['total']) ?> records
    </div>

    <div class="pagination">
        <!-- Previous -->
        <?php if ($current > 1): ?>
            <a href="<?= $pageUrl($current - 1) ?>" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        <?php else: ?>
            <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
        <?php endif; ?>

        <!-- First page -->
        <?php if ($start > 1): ?>
            <a href="<?= $pageUrl(1) ?>">1</a>
            <?php if ($start > 2): ?>
                <span class="ellipsis">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Page numbers -->
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i === $current): ?>
                <span class="active"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= $pageUrl($i) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <!-- Last page -->
        <?php if ($end < $total): ?>
            <?php if ($end < $total - 1): ?>
                <span class="ellipsis">...</span>
            <?php endif; ?>
            <a href="<?= $pageUrl($total) ?>"><?= $total ?></a>
        <?php endif; ?>

        <!-- Next -->
        <?php if ($current < $total): ?>
            <a href="<?= $pageUrl($current + 1) ?>" aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        <?php else: ?>
            <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
        <?php endif; ?>
    </div>
</div>
