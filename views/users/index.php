<div class="page-header">
    <h1><?= htmlspecialchars($pageTitle) ?></h1>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/users/create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add User
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-users empty-state-icon"></i>
                <h3>No Users Found</h3>
                <p>Get started by creating a new user account.</p>
                <a href="<?= BASE_URL ?>/users/create" class="btn btn-primary mt-4">Add First User</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="60">User</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Last Login</th>
                            <th width="100" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="avatar avatar-sm">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                </td>
                                <td class="font-medium"><?= htmlspecialchars($user['name']) ?></td>
                                <td class="text-secondary"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="text-sm text-secondary">
                                    <?= $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?>
                                </td>
                                <td class="actions justify-content-end">
                                    <a href="<?= BASE_URL ?>/users/<?= $user['id'] ?>/edit" class="btn btn-icon btn-ghost btn-sm" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <?php if ($user['id'] !== currentUserId()): ?>
                                        <button type="button" class="btn btn-icon btn-ghost btn-sm text-danger delete-user-btn" 
                                                data-id="<?= $user['id'] ?>" 
                                                data-name="<?= htmlspecialchars($user['name']) ?>"
                                                title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                        <form id="delete-form-<?= $user['id'] ?>" action="<?= BASE_URL ?>/users/<?= $user['id'] ?>/delete" method="POST" class="d-none">                                        </form>
                                    <?php endif; ?>
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
    const deleteBtns = document.querySelectorAll('.delete-user-btn');
    
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            
            if (confirm(`Are you sure you want to delete the user "${name}"? This action cannot be undone.`)) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    });
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
