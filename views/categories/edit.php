<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/categories" class="btn btn-icon btn-outline" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="m-0"><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/categories/<?= $category['id'] ?>/update" method="POST">
            <div class="form-group">
                <label for="name" class="form-label">Category Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" 
                       class="form-control <?= hasError('name') ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars(old('name', $category['name'])) ?>" required>
                <?php if (hasError('name')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('name')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="color" class="form-label">Badge Color</label>
                <div class="color-picker-wrapper">
                    <input type="color" id="color" name="color" 
                           class="form-control <?= hasError('color') ? 'is-invalid' : '' ?>" 
                           value="<?= htmlspecialchars(old('color', $category['color'] ?: '#6366f1')) ?>">
                    <span class="text-sm text-secondary" id="color-hex"><?= htmlspecialchars(old('color', $category['color'] ?: '#6366f1')) ?></span>
                </div>
                <?php if (hasError('color')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('color')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group mb-5">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea id="description" name="description" 
                          class="form-control <?= hasError('description') ? 'is-invalid' : '' ?>" 
                          rows="3"><?= htmlspecialchars(old('description', $category['description'])) ?></textarea>
                <?php if (hasError('description')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('description')) ?></span>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="<?= BASE_URL ?>/categories" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php ob_start(); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const colorInput = document.getElementById('color');
    const colorHex = document.getElementById('color-hex');
    
    colorInput.addEventListener('input', function() {
        colorHex.textContent = this.value.toUpperCase();
    });
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
