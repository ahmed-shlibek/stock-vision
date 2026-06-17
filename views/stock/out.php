<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/stock" class="btn btn-icon btn-outline" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header bg-warning-light text-warning border-bottom-0 rounded-top-lg">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar bg-warning text-dark rounded">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
            </div>
            <div>
                <h3 class="mb-1 text-warning" style="color: #b45309 !important;">Remove Stock</h3>
                <p class="mb-0 text-sm" style="color: #d97706 !important;">Record outgoing items (sales, damage, write-offs)</p>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form action="<?= BASE_URL ?>/stock/out" method="POST" id="stock-out-form">            
            <div class="form-group">
                <label for="product_id" class="form-label">Product <span class="required">*</span></label>
                <select id="product_id" name="product_id" class="form-control <?= hasError('product_id') ? 'is-invalid' : '' ?>" required>
                    <option value="">Select a product...</option>
                    <?php foreach ($products as $prod): ?>
                        <option value="<?= $prod['id'] ?>" data-stock="<?= htmlspecialchars($prod['quantity']) ?>" data-unit="<?= htmlspecialchars($prod['unit']) ?>" <?= $prod['quantity'] == 0 ? 'disabled' : '' ?>>
                            <?= htmlspecialchars($prod['name']) ?> (SKU: <?= htmlspecialchars($prod['sku']) ?>) - <?= $prod['quantity'] == 0 ? 'OUT OF STOCK' : $prod['quantity'] . ' ' . $prod['unit'] . ' available' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (hasError('product_id')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('product_id')) ?></span>
                <?php endif; ?>
                
                <div id="current-stock-info" class="form-text d-none mt-2">
                    Available stock: <strong id="current-stock-val" class="text-primary">0</strong> <span id="current-stock-unit"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="quantity" class="form-label">Quantity to Remove <span class="required">*</span></label>
                <div class="input-icon-wrapper icon-left">
                    <i class="fa-solid fa-minus"></i>
                    <input type="number" id="quantity" name="quantity" 
                           class="form-control <?= hasError('quantity') ? 'is-invalid' : '' ?>" 
                           min="1" required>
                </div>
                <div id="quantity-error" class="form-error d-none mt-1">Cannot remove more than available stock.</div>
                <?php if (hasError('quantity')): ?>
                    <span class="form-error"><?= htmlspecialchars(getError('quantity')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Reason / Notes</label>
                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="e.g. Sold to customer, Damaged in transit..."></textarea>
            </div>

            <div class="mt-5 d-flex justify-content-end gap-3">
                <a href="<?= BASE_URL ?>/stock" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-warning" id="submit-btn">
                    <i class="fa-solid fa-check"></i> Confirm Stock Out
                </button>
            </div>
        </form>
    </div>
</div>

<?php ob_start(); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('stock-out-form');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const stockInfo = document.getElementById('current-stock-info');
    const stockVal = document.getElementById('current-stock-val');
    const stockUnit = document.getElementById('current-stock-unit');
    const quantityError = document.getElementById('quantity-error');
    const submitBtn = document.getElementById('submit-btn');

    let currentAvailable = 0;

    function updateStockInfo() {
        if (productSelect.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            currentAvailable = parseInt(selectedOption.dataset.stock, 10);
            stockVal.textContent = currentAvailable;
            stockUnit.textContent = selectedOption.dataset.unit;
            stockInfo.classList.remove('d-none');
            
            // Re-validate quantity if already entered
            validateQuantity();
        } else {
            stockInfo.classList.add('d-none');
            currentAvailable = 0;
            quantityError.classList.add('d-none');
            submitBtn.disabled = false;
        }
    }

    function validateQuantity() {
        const val = parseInt(quantityInput.value, 10);
        if (!isNaN(val) && val > currentAvailable && currentAvailable > 0) {
            quantityInput.classList.add('is-invalid');
            quantityError.classList.remove('d-none');
            submitBtn.disabled = true;
        } else {
            quantityInput.classList.remove('is-invalid');
            quantityError.classList.add('d-none');
            submitBtn.disabled = false;
        }
    }

    productSelect.addEventListener('change', updateStockInfo);
    quantityInput.addEventListener('input', validateQuantity);
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
