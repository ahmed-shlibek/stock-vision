<div class="toast-container" id="toast-container">
    <!-- Toasts will be injected here by JS -->
</div>

<?php
$flash = getFlash();
if ($flash): 
?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Make sure utils.js is loaded first
    if (typeof showToast === 'function') {
        showToast(
            <?= json_encode($flash['message']) ?>, 
            <?= json_encode($flash['type']) ?>
        );
    } else {
        // Fallback if JS fails to load
        setTimeout(() => {
            if (typeof showToast === 'function') {
                showToast(
                    <?= json_encode($flash['message']) ?>, 
                    <?= json_encode($flash['type']) ?>
                );
            }
        }, 500);
    }
});
</script>
<?php endif; ?>
