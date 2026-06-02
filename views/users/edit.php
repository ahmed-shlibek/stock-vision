<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/users" class="btn btn-icon btn-outline" aria-label="Back to Users">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="m-0"><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/users/<?= $user['id'] ?>/update" method="POST">
            <?= csrfField() ?>

            <div class="form-group">
                <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" 
                       class="form-control <?= hasError('name') ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars(old('name', $user['name'])) ?>" required>
                <?php if (hasError('name')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('name')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" 
                       class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars(old('email', $user['email'])) ?>" required>
                <?php if (hasError('email')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('email')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-row d-flex gap-4 mb-4">
                <div class="form-group flex-1 m-0">
                    <label for="role" class="form-label">Role <span class="required">*</span></label>
                    <select id="role" name="role" class="form-control <?= hasError('role') ? 'is-invalid' : '' ?>" required <?php if($user['id'] === currentUserId()) echo 'disabled'; ?>>
                        <option value="<?= ROLE_EMPLOYEE ?>" <?= old('role', $user['role']) === ROLE_EMPLOYEE ? 'selected' : '' ?>>Employee</option>
                        <option value="<?= ROLE_ADMIN ?>" <?= old('role', $user['role']) === ROLE_ADMIN ? 'selected' : '' ?>>Admin</option>
                        <option value="<?= ROLE_VIEWER ?>" <?= old('role', $user['role']) === ROLE_VIEWER ? 'selected' : '' ?>>Viewer</option>
                    </select>
                    <?php if($user['id'] === currentUserId()): ?>
                        <input type="hidden" name="role" value="<?= htmlspecialchars($user['role']) ?>">
                        <span class="form-text">You cannot change your own role.</span>
                    <?php endif; ?>
                    <?php if (hasError('role')): ?>
                        <span class="form-error"><?= htmlspecialchars(getError('role')) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group flex-1 m-0 d-flex flex-column justify-content-center">
                    <label class="form-label mb-2">Account Status</label>
                    <div class="form-switch">
                        <label class="form-check m-0 align-items-center">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1" <?= old('is_active', $user['is_active']) ? 'checked' : '' ?> <?php if($user['id'] === currentUserId()) echo 'disabled'; ?>>
                            <span class="form-check-label ms-2">Active</span>
                        </label>
                        <?php if($user['id'] === currentUserId()): ?>
                            <input type="hidden" name="is_active" value="1">
                        <?php endif; ?>
                    </div>
                    <?php if($user['id'] === currentUserId()): ?>
                        <span class="form-text mt-1">You cannot deactivate yourself.</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="<?= BASE_URL ?>/users" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
