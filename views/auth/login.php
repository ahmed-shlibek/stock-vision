<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <h1 class="auth-title">Welcome to <?= APP_NAME ?></h1>
        <p class="auth-subtitle">Sign in to manage your inventory</p>
    </div>

    <form action="<?= BASE_URL ?>/login" method="POST" class="auth-form" id="login-form">
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-icon-wrapper icon-left w-100">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" 
                       class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" 
                       placeholder="Enter your email" 
                       value="<?= htmlspecialchars(old('email')) ?>" 
                       required autofocus>
            </div>
            <?php if (hasError('email')): ?>
                <span class="form-error"><?= htmlspecialchars(getError('email')) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-icon-wrapper icon-left icon-right w-100">
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="password" name="password" 
                       class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>" 
                       placeholder="Enter your password" 
                       required>
                <i class="fa-regular fa-eye password-toggle" id="toggle-password" title="Show password"></i>
            </div>
            <?php if (hasError('password')): ?>
                <span class="form-error"><?= htmlspecialchars(getError('password')) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary" id="login-btn">
            <i class="fa-solid fa-right-to-bracket"></i> Sign In
        </button>
    </form>

    <div class="auth-footer">
        &copy; <?= date('Y') ?> <?= APP_NAME ?> v<?= APP_VERSION ?>
    </div>
</div>
