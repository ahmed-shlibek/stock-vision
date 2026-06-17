<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/products" class="btn btn-icon btn-outline" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="m-0"><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
</div>

<form action="<?= BASE_URL ?>/products/<?= $product['id'] ?>/update" method="POST" enctype="multipart/form-data">
    <div class="row">
        <!-- Main Form Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Basic Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Product Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" 
                               class="form-control <?= hasError('name') ? 'is-invalid' : '' ?>" 
                               value="<?= htmlspecialchars(old('name', $product['name'])) ?>" required>
                        <?php if (hasError('name')): ?>
                            <span class="form-error"><?= htmlspecialchars(getError('name')) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="sku" class="form-label">SKU (Stock Keeping Unit) <span class="required">*</span></label>
                        <input type="text" id="sku" name="sku"
                               class="form-control <?= hasError('sku') ? 'is-invalid' : '' ?>"
                               value="<?= htmlspecialchars(old('sku', $product['sku'])) ?>" required>
                        <?php if (hasError('sku')): ?>
                            <span class="form-error"><?= htmlspecialchars(getError('sku')) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-0">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" 
                                  class="form-control <?= hasError('description') ? 'is-invalid' : '' ?>" 
                                  rows="4"><?= htmlspecialchars(old('description', $product['description'])) ?></textarea>
                        <?php if (hasError('description')): ?>
                            <span class="form-error"><?= htmlspecialchars(getError('description')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inventory & Pricing</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit_price" class="form-label">Unit Price ($)</label>
                                <input type="number" step="0.01" min="0" id="unit_price" name="unit_price" 
                                       class="form-control <?= hasError('unit_price') ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars(old('unit_price', $product['unit_price'])) ?>">
                                <?php if (hasError('unit_price')): ?>
                                    <span class="form-error"><?= htmlspecialchars(getError('unit_price')) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit" class="form-label">Unit Type</label>
                                <input type="text" id="unit" name="unit" 
                                       class="form-control <?= hasError('unit') ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars(old('unit', $product['unit'])) ?>">
                                <?php if (hasError('unit')): ?>
                                    <span class="form-error"><?= htmlspecialchars(getError('unit')) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Current Quantity</label>
                                <input type="text" class="form-control bg-secondary-light" 
                                       value="<?= htmlspecialchars($product['quantity']) ?>" readonly disabled>
                                <small class="text-secondary mt-1 d-block">Quantity must be updated via Stock Movements.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_stock_level" class="form-label">Low Stock Alert Level</label>
                                <input type="number" min="0" id="min_stock_level" name="min_stock_level" 
                                       class="form-control <?= hasError('min_stock_level') ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars(old('min_stock_level', $product['min_stock_level'])) ?>">
                                <?php if (hasError('min_stock_level')): ?>
                                    <span class="form-error"><?= htmlspecialchars(getError('min_stock_level')) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Organization</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Category</label>
                        <select id="category_id" name="category_id" class="form-control <?= hasError('category_id') ? 'is-invalid' : '' ?>">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= old('category_id', $product['category_id']) == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="form-control <?= hasError('supplier_id') ? 'is-invalid' : '' ?>">
                            <option value="">Select Supplier</option>
                            <?php foreach ($suppliers as $sup): ?>
                                <option value="<?= $sup['id'] ?>" <?= old('supplier_id', $product['supplier_id']) == $sup['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sup['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Product Image</h3>
                </div>
                <div class="card-body">
                    <div class="form-group mb-0">
                        <div class="image-upload-wrapper text-center p-4 border rounded" style="border-style: dashed !important; background: var(--bg-secondary);">
                            <img id="image-preview" 
                                 src="<?= $product['image'] ? BASE_URL . '/' . htmlspecialchars($product['image']) : '' ?>" 
                                 alt="Preview" 
                                 style="max-width: 100%; max-height: 200px; display: <?= $product['image'] ? 'block' : 'none' ?>; margin: 0 auto 15px auto; border-radius: var(--radius-md);">
                            
                            <i class="fa-solid fa-cloud-arrow-up text-secondary fa-3x mb-3" id="upload-icon" style="display: <?= $product['image'] ? 'none' : 'block' ?>;"></i>
                            <p class="text-secondary mb-3">Click to choose a file</p>
                            
                            <input type="file" id="image" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" style="display: none;">
                            <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('image').click()">
                                Choose File
                            </button>
                        </div>
                        <?php if (hasError('image')): ?>
                            <span class="form-error d-block mt-2"><?= htmlspecialchars(getError('image')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card bg-transparent border-0 shadow-none">
                <div class="d-flex gap-3 w-100">
                    <a href="<?= BASE_URL ?>/products" class="btn btn-outline flex-1">Cancel</a>
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="<?= BASE_URL ?>/js/products.js"></script>
