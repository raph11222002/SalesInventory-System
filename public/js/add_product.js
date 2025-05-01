document.addEventListener('DOMContentLoaded', function () {
    // Image preview functionality
    const productImage = document.getElementById('productImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImage');

    productImage.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';

                // Remove error message if it exists
                const imageError = document.getElementById('imageError');
                if (imageError) {
                    imageError.remove();
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality
    removeImageBtn.addEventListener('click', function () {
        // Clear the file input
        productImage.value = '';
        // Hide the preview
        imagePreview.style.display = 'none';
        // Clear the image source
        previewImg.src = '';
    });

    // Add inventory item functionality
    let itemCount = document.querySelectorAll('.inventory-item').length;

    document.getElementById('add-inventory-item').addEventListener('click', function () {
        const container = document.getElementById('inventory-items-container');
        const newItem = document.createElement('div');
        newItem.className = 'flex flex-col md:flex-row items-start md:items-end mb-4 gap-4 inventory-item';
        newItem.innerHTML = `
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-1">Inventory Name
                    <span class="text-red-500">*</span></label>
                <input type="text" name="inventory_items[${itemCount}][name]" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            </div>
            
            <button type="button" class="remove-item text-white bg-red-600 hover:bg-red-700 px-4 py-2.5 rounded-md text-sm mt-2 md:mt-0">
                <i class="fas fa-trash mr-1"></i> Remove
            </button>
        `;
        container.appendChild(newItem);
        itemCount++;

        // Add event listener to the new remove button
        newItem.querySelector('.remove-item').addEventListener('click', function () {
            container.removeChild(newItem);
            updateInventoryItemIndexes();
        });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function () {
            if (document.querySelectorAll('.inventory-item').length > 1) {
                this.closest('.inventory-item').remove();
                updateInventoryItemIndexes();
            }
        });
    });

    // Function to update inventory item indexes
    function updateInventoryItemIndexes() {
        const items = document.querySelectorAll('.inventory-item');
        items.forEach((item, index) => {
            const input = item.querySelector('input[name^="inventory_items"]');
            input.name = `inventory_items[${index}][name]`;
        });
        itemCount = items.length;
    }

    // Form submission
    document.getElementById('productForm').addEventListener('submit', function (event) {
        // Check if image is provided
        const imageInput = document.getElementById('productImage');
        const imageError = document.getElementById('imageError');

        if (!imageInput.files || imageInput.files.length === 0) {
            event.preventDefault(); // Prevent form submission

            // Create error message if it doesn't exist
            if (!imageError) {
                const errorDiv = document.createElement('div');
                errorDiv.id = 'imageError';
                errorDiv.className = 'text-red-500 text-sm mt-1';
                errorDiv.textContent = 'Please select a product image';
                imageInput.parentNode.insertBefore(errorDiv, document.getElementById('imagePreview'));
            }

            // Scroll to the error
            imageInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        } else if (imageError) {
            // Remove error message if it exists and image is now provided
            imageError.remove();
        }

        // Continue with submission
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Open the modal when the "Add Stock" button is clicked
    const openModalButton = document.getElementById('openModalButton');
    const modal = document.getElementById('stockModal');
    const closeModalButton = document.getElementById('closeModalButton');
    const inventoryIdInput = document.getElementById('inventory-id');
    const form = document.getElementById('addStockForm');

    // When the "Add Stock" button is clicked
    openModalButton.addEventListener('click', function (e) {
        e.preventDefault();

        // Get the inventory ID from the button data attribute
        const inventoryId = openModalButton.getAttribute('data-inventory-id');

        // Set the inventory ID to the hidden input
        inventoryIdInput.value = inventoryId;

        // Show the modal
        modal.classList.remove('hidden');
    });

    // When the "Cancel" button is clicked
    closeModalButton.addEventListener('click', function () {
        // Hide the modal
        modal.classList.add('hidden');
    });

    // Optionally, handle form submission (e.g., submit via AJAX)
    form.addEventListener('submit', function (e) {
        // You can handle your form submission here, maybe via AJAX
        e.preventDefault();

        // Submit form (use AJAX or regular form submit)
        form.submit();
    });
});

