<div class="page-header">
    <h1><?= htmlspecialchars($pageTitle) ?></h1>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/categories/create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Category
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-tags empty-state-icon"></i>
                <h3>No Categories Found</h3>
                <p>Create categories to organize your products efficiently.</p>
                <a href="<?= BASE_URL ?>/categories/create" class="btn btn-primary mt-4">Add First Category</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="50">Color</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="100" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td>
                                    <div style="width: 24px; height: 24px; border-radius: 4px; background-color: <?= htmlspecialchars($cat['color'] ?: '#6366f1') ?>; border: 1px solid rgba(0,0,0,0.1);"></div>
                                </td>
                                <td class="font-medium"><?= htmlspecialchars($cat['name']) ?></td>
                                <td class="text-secondary text-truncate" style="max-width: 300px;">
                                    <?= htmlspecialchars($cat['description'] ?: '-') ?>
                                </td>
                                <td class="text-right">
                                    <div class="actions justify-content-end">
                                        <a href="<?= BASE_URL ?>/categories/<?= $cat['id'] ?>/edit" class="btn btn-icon btn-ghost btn-sm" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-icon btn-ghost btn-sm text-danger delete-btn" 
                                                data-id="<?= $cat['id'] ?>" 
                                                data-name="<?= htmlspecialchars($cat['name']) ?>"
                                                title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                        <form id="delete-form-<?= $cat['id'] ?>" action="<?= BASE_URL ?>/categories/<?= $cat['id'] ?>/delete" method="POST" class="d-none">
                                            <?= csrfField() ?>
                                        </form>
                                    </div>
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

<?php ob_start(); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm(`Are you sure you want to delete the category "${this.dataset.name}"? Products in this category will remain but lose this categorization.`)) {
                document.getElementById(`delete-form-${this.dataset.id}`).submit();
            }
        });
    });
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
