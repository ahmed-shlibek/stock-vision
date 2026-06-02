<div class="page-header">
    <h1><?= htmlspecialchars($pageTitle) ?></h1>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/suppliers/create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Supplier
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($suppliers)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-truck-field empty-state-icon"></i>
                <h3>No Suppliers Found</h3>
                <p>Add suppliers to track where your inventory comes from.</p>
                <a href="<?= BASE_URL ?>/suppliers/create" class="btn btn-primary mt-4">Add First Supplier</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Info</th>
                            <th>Status</th>
                            <th width="100" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $sup): ?>
                            <tr>
                                <td class="font-medium">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm bg-primary-light text-primary rounded">
                                            <?= strtoupper(substr($sup['name'], 0, 1)) ?>
                                        </div>
                                        <?= htmlspecialchars($sup['name']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <?php if ($sup['email']): ?>
                                            <div><i class="fa-regular fa-envelope text-secondary mr-1"></i> <?= htmlspecialchars($sup['email']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($sup['phone']): ?>
                                            <div><i class="fa-solid fa-phone text-secondary mr-1"></i> <?= htmlspecialchars($sup['phone']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!$sup['email'] && !$sup['phone']): ?>
                                            <span class="text-secondary">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($sup['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <div class="actions justify-content-end">
                                        <a href="<?= BASE_URL ?>/suppliers/<?= $sup['id'] ?>/edit" class="btn btn-icon btn-ghost btn-sm" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-icon btn-ghost btn-sm text-danger delete-btn" 
                                                data-id="<?= $sup['id'] ?>" 
                                                data-name="<?= htmlspecialchars($sup['name']) ?>"
                                                title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                        <form id="delete-form-<?= $sup['id'] ?>" action="<?= BASE_URL ?>/suppliers/<?= $sup['id'] ?>/delete" method="POST" class="d-none">
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
            if (confirm(`Are you sure you want to delete the supplier "${this.dataset.name}"? Products from this supplier will remain but lose this association.`)) {
                document.getElementById(`delete-form-${this.dataset.id}`).submit();
            }
        });
    });
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
