/**
 * StockVision - Auth Script
 * Login form handling and interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const loginBtn = document.getElementById('login-btn');
    
    if (loginForm && loginBtn) {
        loginForm.addEventListener('submit', () => {
            // Add loading state to button
            loginBtn.innerHTML = '<div class="loading-spinner sm" style="border-top-color: white;"></div> Signing In...';
            loginBtn.disabled = true;
            loginBtn.style.opacity = '0.8';
            loginBtn.style.cursor = 'not-allowed';
        });
    }

    // Password visibility toggle (handled in specific view scripts now, but could be globalized here)
    const togglePasswords = document.querySelectorAll('.password-toggle');
    togglePasswords.forEach(toggle => {
        // Prevent duplicate listeners if already attached in view
        if (!toggle.hasAttribute('data-initialized')) {
            toggle.setAttribute('data-initialized', 'true');
            toggle.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input && input.tagName === 'INPUT') {
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                }
            });
        }
    });
});
