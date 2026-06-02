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
        <form action="<?= BASE_URL ?>/users/store" method="POST">
            <?= csrfField() ?>

            <div class="form-group">
                <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" 
                       class="form-control <?= hasError('name') ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars(old('name')) ?>" required autofocus>
                <?php if (hasError('name')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('name')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" 
                       class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars(old('email')) ?>" required>
                <?php if (hasError('email')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('email')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-row d-flex gap-4 mb-4">
                <div class="form-group flex-1 m-0">
                    <label for="role" class="form-label">Role <span class="required">*</span></label>
                    <select id="role" name="role" class="form-control <?= hasError('role') ? 'is-invalid' : '' ?>" required>
                        <option value="<?= ROLE_EMPLOYEE ?>" <?= old('role') === ROLE_EMPLOYEE ? 'selected' : '' ?>>Employee</option>
                        <option value="<?= ROLE_ADMIN ?>" <?= old('role') === ROLE_ADMIN ? 'selected' : '' ?>>Admin</option>
                        <option value="<?= ROLE_VIEWER ?>" <?= old('role') === ROLE_VIEWER ? 'selected' : '' ?>>Viewer</option>
                    </select>
                    <?php if (hasError('role')): ?>
                        <span class="form-error"><?= htmlspecialchars(getError('role')) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group flex-1 m-0 d-flex flex-column justify-content-center">
                    <label class="form-label mb-2">Account Status</label>
                    <div class="form-switch">
                        <label class="form-check m-0 align-items-center">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1" <?= old('is_active', '1') ? 'checked' : '' ?>>
                            <span class="form-check-label ms-2">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="form-group">
                <label for="password" class="form-label">Initial Password <span class="required">*</span></label>
                <div class="input-icon-wrapper icon-right">
                    <input type="password" id="password" name="password" 
                           class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>" required minlength="8">
                    <i class="fa-regular fa-eye password-toggle"></i>
                </div>
                <span class="form-text">Must be at least 8 characters.</span>
                <?php if (hasError('password')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('password')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group mb-5">
                <label for="password_confirm" class="form-label">Confirm Password <span class="required">*</span></label>
                <div class="input-icon-wrapper icon-right">
                    <input type="password" id="password_confirm" name="password_confirm" 
                           class="form-control <?= hasError('password_confirm') ? 'is-invalid' : '' ?>" required minlength="8">
                    <i class="fa-regular fa-eye password-toggle"></i>
                </div>
                <?php if (hasError('password_confirm')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('password_confirm')) ?></span>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="<?= BASE_URL ?>/users" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>

<?php ob_start(); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Password visibility toggle
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
