/**
 * StockVision - Products JavaScript
 * Handles product image preview
 */

document.addEventListener('DOMContentLoaded', () => {
    initImagePreview();
});

/**
 * Image Upload Preview
 */
function initImagePreview() {
    const fileInput = document.getElementById('image');
    if (!fileInput) return;

    const preview = document.getElementById('image-preview');
    const uploadIcon = document.getElementById('upload-icon');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                uploadIcon.style.display = 'none';
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            // Revert if empty
            if (!preview.src || preview.src.endsWith('preview')) {
                preview.style.display = 'none';
                uploadIcon.style.display = 'block';
            }
        }
    });
}
