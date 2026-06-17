<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/suppliers" class="btn btn-icon btn-outline" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="m-0"><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/suppliers/store" method="POST">
            <div class="form-group">
                <label for="name" class="form-label">Supplier Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" 
                       class="form-control <?= hasError('name') ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars(old('name')) ?>" required autofocus>
                <?php if (hasError('name')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('name')) ?></span>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" 
                               class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" 
                               value="<?= htmlspecialchars(old('email')) ?>">
                        <?php if (hasError('email')): ?>
                            <span class="form-error"><?= htmlspecialchars(getError('email')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" id="phone" name="phone" 
                               class="form-control <?= hasError('phone') ? 'is-invalid' : '' ?>" 
                               value="<?= htmlspecialchars(old('phone')) ?>">
                        <?php if (hasError('phone')): ?>
                            <span class="form-error"><?= htmlspecialchars(getError('phone')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Physical Address</label>
                <textarea id="address" name="address" 
                          class="form-control <?= hasError('address') ? 'is-invalid' : '' ?>" 
                          rows="3"><?= htmlspecialchars(old('address')) ?></textarea>
                <?php if (hasError('address')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('address')) ?></span>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="<?= BASE_URL ?>/suppliers" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Save Supplier
                </button>
            </div>
        </form>
    </div>
</div>
