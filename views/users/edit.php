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

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="<?= BASE_URL ?>/users" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
