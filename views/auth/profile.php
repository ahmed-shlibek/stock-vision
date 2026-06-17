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
            <form action="<?= BASE_URL ?>/profile" method="POST">
                <div class="form-group text-center">
                    <div class="avatar" style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto; font-size: 2.5rem; display: flex; align-items: center; justify-content: center;">
                        <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                    </div>
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

