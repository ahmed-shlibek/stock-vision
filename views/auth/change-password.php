<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/profile" class="btn btn-icon btn-outline" aria-label="Back to Profile">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="m-0"><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
</div>

<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/change-password" method="POST">
            <div class="form-group">
                <label for="current_password" class="form-label">Current Password <span class="required">*</span></label>
                <div class="input-icon-wrapper icon-right">
                    <input type="password" id="current_password" name="current_password" 
                           class="form-control <?= hasError('current_password') ? 'is-invalid' : '' ?>" required>
                    <i class="fa-regular fa-eye password-toggle"></i>
                </div>
                <?php if (hasError('current_password')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('current_password')) ?></span>
                <?php endif; ?>
            </div>

            <hr class="my-4">

            <div class="form-group">
                <label for="new_password" class="form-label">New Password <span class="required">*</span></label>
                <div class="input-icon-wrapper icon-right">
                    <input type="password" id="new_password" name="new_password" 
                           class="form-control <?= hasError('new_password') ? 'is-invalid' : '' ?>" required minlength="8">
                    <i class="fa-regular fa-eye password-toggle"></i>
                </div>
                <span class="form-text">Must be at least 8 characters long.</span>
                <?php if (hasError('new_password')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('new_password')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group mb-5">
                <label for="password_confirm" class="form-label">Confirm New Password <span class="required">*</span></label>
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
                <a href="<?= BASE_URL ?>/profile" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-key"></i> Update Password
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
