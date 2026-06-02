/**
 * StockVision - Shared Utilities
 * Common JavaScript functions used across the application
 */

// Base URL for API calls
const BASE_URL = document.querySelector('meta[name="base-url"]')?.content || '';

/**
 * Fetch wrapper with CSRF support and JSON handling
 */
async function fetchAPI(url, options = {}) {
    const defaultHeaders = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };

    // Auto-attach CSRF token for POST requests if available in DOM
    if (options.method && options.method.toUpperCase() === 'POST') {
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput && !options.body) {
            // If body is FormData, we should append it there instead, but for simple fetch:
            if (!(options.body instanceof FormData)) {
                if (!options.headers) options.headers = {};
                // If it's a JSON POST
                if (options.headers['Content-Type'] === 'application/json') {
                    let body = JSON.parse(options.body || '{}');
                    body.csrf_token = csrfInput.value;
                    options.body = JSON.stringify(body);
                }
            }
        }
    }

    options.headers = { ...defaultHeaders, ...options.headers };

    try {
        const response = await fetch(BASE_URL + url, options);
        
        // Handle non-JSON responses gracefully
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }
            return data;
        } else {
            if (!response.ok) {
                throw new Error('API request failed');
            }
            return await response.text();
        }
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

/**
 * Show a toast notification
 * type: 'success', 'error', 'warning', 'info'
 */
function showToast(message, type = 'success', duration = 4000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Map 'danger' to 'error' for CSS classes
    const cssType = type === 'danger' ? 'error' : type;
    
    // Icon mapping
    const icons = {
        success: 'fa-circle-check',
        error: 'fa-circle-exclamation',
        danger: 'fa-circle-exclamation',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info'
    };

    const icon = icons[type] || icons.info;

    const toast = document.createElement('div');
    toast.className = `toast toast-${cssType}`;
    
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fa-solid ${icon}"></i>
        </div>
        <div class="toast-message">${escapeHtml(message)}</div>
        <button class="toast-close" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="toast-progress" style="animation-duration: ${duration}ms"></div>
    `;

    container.appendChild(toast);

    // Setup close button
    const closeBtn = toast.querySelector('.toast-close');
    
    const removeToast = () => {
        toast.classList.add('removing');
        toast.addEventListener('animationend', () => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        });
    };

    closeBtn.addEventListener('click', removeToast);

    // Auto dismiss
    setTimeout(removeToast, duration);
}

/**
 * Format number as currency (USD)
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', { 
        style: 'currency', 
        currency: 'USD' 
    }).format(amount);
}

/**
 * Format date string
 */
function formatDate(dateString) {
    if (!dateString) return '';
    return new Intl.DateTimeFormat('en-US', { 
        dateStyle: 'medium', 
        timeStyle: 'short' 
    }).format(new Date(dateString));
}

/**
 * Format number with commas
 */
function formatNumber(num) {
    if (num === null || num === undefined) return '0';
    return new Intl.NumberFormat().format(num);
}

/**
 * Debounce function for inputs
 */
function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') return unsafe;
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}
