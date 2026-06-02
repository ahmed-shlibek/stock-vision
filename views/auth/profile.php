<div class="page-header">
    <h1><?= htmlspecialchars($pageTitle) ?></h1>
</div>

<div class="grid grid-2">
    <!-- Profile Form -->
    <div class="card">
        <div class="card-header">
            <h3>Personal Information</h3>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>/profile" method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>

                <div class="form-group text-center">
                    <div class="file-upload mb-3" style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto; padding: 0; overflow: hidden; position: relative;">
                        <?php if ($user['avatar']): ?>
                            <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" 
                                 id="avatar-preview" 
                                 style="width: 100%; height: 100%; object-fit: cover;" 
                                 alt="Avatar">
                        <?php else: ?>
                            <img src="" id="avatar-preview" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="Avatar">
                            <i class="fa-solid fa-camera" id="avatar-placeholder" style="font-size: 2rem;"></i>
                        <?php endif; ?>
                        
                        <div class="upload-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); color: white; padding: 4px; font-size: 10px; opacity: 0; transition: opacity 0.2s;">
                            Click to change
                        </div>
                        <input type="file" name="avatar" id="avatar-input" accept="image/jpeg, image/png, image/webp" title="Change Avatar">
                    </div>
                    <?php if (hasError('avatar')): ?>
                        <span class="form-error"><?= htmlspecialchars(getError('avatar')) ?></span>
                    <?php endif; ?>
                    <p class="form-text mt-2">Max size: 2MB. JPEG, PNG, or WebP.</p>
                </div>

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

                <div class="form-row d-flex gap-4">
                    <div class="form-group flex-1">
                        <label class="form-label">Role</label>
                        <div>
                            <span class="badge badge-primary" style="font-size: var(--font-size-sm); padding: 6px 12px;">
                                <?= ucfirst(htmlspecialchars($user['role'])) ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group flex-1">
                        <label class="form-label">Account Status</label>
                        <div>
                            <span class="badge badge-success" style="font-size: var(--font-size-sm); padding: 6px 12px;">
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Info -->
    <div>
        <div class="card mb-6">
            <div class="card-header">
                <h3>Security</h3>
            </div>
            <div class="card-body">
                <p class="text-secondary mb-4">Ensure your account is using a long, random password to stay secure.</p>
                <a href="<?= BASE_URL ?>/change-password" class="btn btn-outline w-100">
                    <i class="fa-solid fa-key"></i> Change Password
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Account Activity</h3>
            </div>
            <div class="card-body">
                <ul class="text-sm text-secondary" style="line-height: 2;">
                    <li><strong>Account Created:</strong> <?= date('F j, Y, g:i a', strtotime($user['created_at'])) ?></li>
                    <li><strong>Last Login:</strong> <?= $user['last_login'] ? date('F j, Y, g:i a', strtotime($user['last_login'])) : 'Never' ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarPlaceholder = document.getElementById('avatar-placeholder');
    const uploadContainer = document.querySelector('.file-upload');
    const overlay = document.querySelector('.upload-overlay');

    if (!avatarInput) return;

    uploadContainer.addEventListener('mouseenter', () => overlay.style.opacity = '1');
    uploadContainer.addEventListener('mouseleave', () => overlay.style.opacity = '0');

    avatarInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Basic client side validation
            if (file.size > 2 * 1024 * 1024) {
                showToast('Image must be smaller than 2MB', 'error');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                avatarPreview.style.display = 'block';
                if (avatarPlaceholder) avatarPlaceholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
<?php $pageScripts = ob_get_clean(); ?>
