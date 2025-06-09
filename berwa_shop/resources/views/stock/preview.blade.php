<!-- Preview Section -->
<div class="mb-3 border-top pt-3">
    <label class="form-label">Stock Out Preview</label>
    <div class="alert alert-info" id="previewText">
        Please select a product and enter quantity to see preview.
    </div>
</div>

@push('scripts')
<script>
function updatePreview() {
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const quantity = parseInt(quantityInput.value) || 0;
    const unitPrice = parseFloat(unitPriceInput.value) || 0;
    const total = quantity * unitPrice;

    if (selectedOption.value && quantity > 0) {
        const productName = selectedOption.text.split(' (')[0];
        const availableStock = parseInt(selectedOption.dataset.available);
        let previewMessage = `
            <strong>Stock Out Summary:</strong><br>
            Product: ${productName}<br>
            Quantity to stock out: ${quantity} units<br>
            Available after stock out: ${availableStock - quantity} units<br>
            Unit Price: $${unitPrice.toFixed(2)}<br>
            Total Price: $${total.toFixed(2)}
        `;

        // Add warning if stock will be low after this transaction
        if ((availableStock - quantity) <= 10 && (availableStock - quantity) > 0) {
            previewMessage += '<br><br><span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Warning: Stock will be low after this transaction.</span>';
        } else if ((availableStock - quantity) === 0) {
            previewMessage += '<br><br><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Warning: This will deplete the entire stock!</span>';
        }

        previewText.innerHTML = previewMessage;
        previewText.className = 'alert alert-info';
    } else {
        previewText.innerHTML = 'Please select a product and enter quantity to see preview.';
        previewText.className = 'alert alert-info';
    }
}

// Add these event listeners after your existing ones
productSelect.addEventListener('change', updatePreview);
quantityInput.addEventListener('input', updatePreview);
unitPriceInput.addEventListener('input', updatePreview);

// Call updatePreview on initial load
updatePreview();
</script>
@endpush 