/**
 * StockVision - Products JavaScript
 * Handles product image preview and barcode rendering
 */

document.addEventListener('DOMContentLoaded', () => {
    initImagePreview();
    initBarcode();
});

/**
 * Image Upload Preview
 */
function initImagePreview() {
    const fileInput = document.getElementById('image');
    if (!fileInput) return;

    const preview = document.getElementById('image-preview');
    const uploadIcon = document.getElementById('upload-icon');
    const wrapper = document.querySelector('.image-upload-wrapper');

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

    // Drag and drop support
    if (wrapper) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            wrapper.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            wrapper.addEventListener(eventName, () => {
                wrapper.style.borderColor = 'var(--primary)';
                wrapper.style.backgroundColor = 'rgba(99, 102, 241, 0.05)';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            wrapper.addEventListener(eventName, () => {
                wrapper.style.borderColor = '';
                wrapper.style.backgroundColor = 'var(--bg-secondary)';
            }, false);
        });

        wrapper.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files && files.length > 0) {
                fileInput.files = files;
                // Dispatch change event manually
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        }, false);
    }
}

/**
 * Barcode Generation using JsBarcode
 */
function initBarcode() {
    const barcodeSvg = document.getElementById('barcode-svg');
    if (!barcodeSvg) return;

    const value = barcodeSvg.dataset.value;
    if (value && typeof JsBarcode !== 'undefined') {
        try {
            JsBarcode("#barcode-svg", value, {
                format: "CODE128",
                lineColor: "#000",
                width: 2,
                height: 50,
                displayValue: true,
                fontSize: 14,
                margin: 0
            });
        } catch (e) {
            console.error("Error generating barcode:", e);
            barcodeSvg.outerHTML = '<div class="text-danger text-sm">Failed to generate barcode. Invalid format.</div>';
        }
    }
}
