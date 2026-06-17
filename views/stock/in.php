<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/stock" class="btn btn-icon btn-outline" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header bg-success-light text-success border-bottom-0 rounded-top-lg">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar bg-success text-white rounded">
                <i class="fa-solid fa-arrow-down-to-line"></i>
            </div>
            <div>
                <h3 class="mb-1 text-success">Add Stock</h3>
                <p class="mb-0 text-sm opacity-75">Record incoming items (purchases, returns, etc.)</p>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form action="<?= BASE_URL ?>/stock/in" method="POST">            
            <div class="form-group">
                <label for="product_id" class="form-label">Product <span class="required">*</span></label>
                <select id="product_id" name="product_id" class="form-control <?= hasError('product_id') ? 'is-invalid' : '' ?>" required>
                    <option value="">Select a product...</option>
                    <?php foreach ($products as $prod): ?>
                        <option value="<?= $prod['id'] ?>" data-stock="<?= htmlspecialchars($prod['quantity']) ?>" data-unit="<?= htmlspecialchars($prod['unit']) ?>">
                            <?= htmlspecialchars($prod['name']) ?> (SKU: <?= htmlspecialchars($prod['sku']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (hasError('product_id')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('product_id')) ?></span>
                <?php endif; ?>
                
                <div id="current-stock-info" class="form-text d-none mt-2">
                    Current stock: <strong id="current-stock-val">0</strong> <span id="current-stock-unit"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="quantity" class="form-label">Quantity to Add <span class="required">*</span></label>
                <div class="input-icon-wrapper icon-left">
                    <i class="fa-solid fa-plus"></i>
                    <input type="number" id="quantity" name="quantity" 
                           class="form-control <?= hasError('quantity') ? 'is-invalid' : '' ?>" 
                           min="1" required>
                </div>
                <?php if (hasError('quantity')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('quantity')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Reason / Notes</label>
                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="e.g. Purchase Order #12345, Customer Return..."></textarea>
            </div>

            <div class="mt-5 d-flex justify-content-end gap-3">
                <a href="<?= BASE_URL ?>/stock" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check"></i> Confirm Stock In
                </button>
            </div>
        </form>
    </div>
</div>

<?php ob_start(); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const productSelect = document.getElementById('product_id');
    const stockInfo = document.getElementById('current-stock-info');
    const stockVal = document.getElementById('current-stock-val');
    const stockUnit = document.getElementById('current-stock-unit');

    productSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            stockVal.textContent = selectedOption.dataset.stock;
            stockUnit.textContent = selectedOption.dataset.unit;
            stockInfo.classList.remove('d-none');
        } else {
            stockInfo.classList.add('d-none');
        }
    });
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
