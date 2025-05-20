document.addEventListener('DOMContentLoaded', function () {
    // ===== IMAGE HANDLING =====
    initializeImageHandling();

    // ===== CONSUMABLE ITEMS HANDLING =====
    initializeConsumableItems();

    // ===== FORM VALIDATION =====
    initializeFormValidation();

    // ===== HSSELECT INITIALIZATION =====
    initializeHSSelectValidation();

    // ===== CHECKBOX TOGGLE =====
    initializeConsumableCheckboxToggle();

    initializeAddConsumableItemButton();
});

const noConsumableCheckbox = document.getElementById('product-consumable-checkbox');
const requiredStockCheckbox = document.getElementById('product-required-stock-checkbox');
const warningMsg = document.getElementById('warning-msg');

function validateCheckboxes() {
    if (noConsumableCheckbox.checked && !requiredStockCheckbox.checked) {
        warningMsg.classList.remove('hidden');
        // Optional: Auto-correct or disable form submission
        requiredStockCheckbox.checked = true;
    } else {
        warningMsg.classList.add('hidden');
    }
}

noConsumableCheckbox.addEventListener('change', validateCheckboxes);
requiredStockCheckbox.addEventListener('change', validateCheckboxes);

// Image preview and handling functionality
function initializeImageHandling() {
    const productImage = document.getElementById('productImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImage');

    if (!productImage || !imagePreview || !previewImg || !removeImageBtn) {
        console.warn('One or more image handling elements not found');
        return;
    }

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
            };

            reader.onerror = function () {
                displayErrorMessage(productImage, 'imageError', 'Failed to load image. Please try another file.');
            };

            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality
    removeImageBtn.addEventListener('click', function () {
        productImage.value = '';
        imagePreview.style.display = 'none';
        previewImg.src = '';
    });
}

// Consumable items handling
function initializeConsumableItems() {
    let itemCount = document.querySelectorAll('.consumable-item').length;
    const addButton = document.getElementById('add-consumable-item');
    const container = document.getElementById('consumable-items-container');

    if (!addButton || !container) {
        console.warn('Consumable items container or add button not found');
        return;
    }

    // Initialize existing items
    document.querySelectorAll('.consumable-item').forEach(item => {
        attachCounterEvents(item);
    });

    // Attach remove event to existing remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function () {
            const items = document.querySelectorAll('.consumable-item');
            const item = this.closest('.consumable-item');

            // Don't allow removal of the first item
            if (items.length > 1 && item !== items[0]) {
                item.remove();
                updateConsumableItemIndexes();
            }
        });
    });

    // Add new consumable item
    addButton.addEventListener('click', function () {
        const currentIndex = itemCount;

        const newItem = document.createElement('div');
        newItem.className = 'flex flex-col md:flex-row items-start md:items-end mb-4 gap-4 consumable-item';
        newItem.innerHTML = `
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-1">Consumable Name [${currentIndex + 1}]
                    <span class="text-red-500">*</span>
                </label>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Select with Search -->
                    <div class="flex-1 min-w-[200px]">
                        <select id="consumable-select-${currentIndex}"
                            name="consumable[${currentIndex}][name]" data-hs-select='{
                                "placeholder": "Search consumable...",
                                "hasSearch": true,
                                "searchPlaceholder": "Search consumables...",
                                "searchClasses": "block w-full sm:text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-1.5 sm:py-2 px-3",
                                "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                                "toggleTag": "<button type=\\"button\\" aria-expanded=\\"false\\"></button>",
                                "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600",
                                "dropdownClasses": "mt-2 z-50 w-full max-h-72 pb-1 px-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                                "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                                "optionTemplate": "<div class=\\"flex justify-between items-center w-full\\"><span data-title></span><span class=\\"hidden hs-selected:block\\"><svg class=\\"shrink-0 size-3.5 text-blue-600 dark:text-blue-500\\" xmlns=\\"http://www.w3.org/2000/svg\\" width=\\"24\\" height=\\"24\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"2\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\"><polyline points=\\"20 6 9 17 4 12\\"/></svg></span></div>",
                                "extraMarkup": "<div class=\\"absolute top-1/2 end-3 -translate-y-1/2\\"><svg class=\\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500\\" xmlns=\\"http://www.w3.org/2000/svg\\" width=\\"24\\" height=\\"24\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"2\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\"><path d=\\"m7 15 5 5 5-5\\"/><path d=\\"m7 9 5-5 5 5\\"/></svg></div>"
                            }' class="selection">
                            <option value="">Select a consumable</option>
                            ${generateConsumableOptions()}
                        </select>
                    </div>

                    <!-- Quantity Input -->
                    <div class="relative flex items-center w-[11rem]">
                        <button type="button"
                            class="decrement bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 border-gray-600 hover:bg-gray-200 border rounded-s-lg p-3 h-11 focus:ring-blue-300 focus:ring-2 focus:outline-none">
                            <svg class="w-3 h-3 text-gray-900 dark:text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                <path stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                            </svg>
                        </button>

                        <input type="text" name="consumable[${currentIndex}][quantity_needed]"
                            value="1" min="1"
                            class="quantity-needed bg-gray-50 border-x-0 h-11 font-medium text-center text-gray-900 text-sm focus:ring-blue-300 block w-full pb-6 border-gray-600 placeholder-gray-400 focus:border-blue-300"
                            required />

                        <div class="absolute bottom-1 start-1/2 -translate-x-1/2 flex items-center text-xs text-gray-900">
                            <span>Quantity</span>
                        </div>

                        <button type="button"
                            class="increment bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 border-gray-600 hover:bg-gray-200 border rounded-e-lg p-3 h-11 focus:ring-blue-300 focus:ring-2 focus:outline-none">
                            <svg class="w-3 h-3 text-gray-900 dark:text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Remove Button -->
                    <button type="button"
                        class="remove-item bg-red-500 hover:bg-red-600 px-3 py-2.5 rounded-md text-sm mt-2 md:mt-0 border-none hover:opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="select-error text-red-500 text-sm mt-1" style="display: none;">Please select a consumable item</div>
            </div>
        `;

        container.appendChild(newItem);
        itemCount++;

        // Initialize the HS Select component for the newly added dropdown
        if (typeof HSSelect !== 'undefined') {
            try {
                HSSelect.autoInit();
            } catch (error) {
                console.error('Error initializing HSSelect:', error);
            }
        }

        // Bind remove event to this item
        newItem.querySelector('.remove-item').addEventListener('click', function () {
            container.removeChild(newItem);
            updateConsumableItemIndexes();
        });

        // Bind counter and validation events
        attachCounterEvents(newItem);
        attachValidationEvents(newItem);
    });

    // Helper function to generate consumable options from existing template
    function generateConsumableOptions() {
        const templateSelect = document.querySelector('#consumable-select-template') ||
            document.querySelector('select[name^="consumable"][name$="[name]"]');

        if (templateSelect) {
            return Array.from(templateSelect.querySelectorAll('option'))
                .map(option => {
                    if (option.value === '') return '<option value="">Select a consumable</option>';
                    return `<option value="${option.value}">${option.textContent}</option>`;
                })
                .join('');
        }

        // Fallback template string
        return '<option value="">Select a consumable</option>';
    }

    // Attach counter events to consumable item
    function attachCounterEvents(item) {
        const decrementBtn = item.querySelector('.decrement');
        const incrementBtn = item.querySelector('.increment');
        const input = item.querySelector('.quantity-needed');

        if (!decrementBtn || !incrementBtn || !input) return;

        incrementBtn.addEventListener('click', () => {
            let value = parseInt(input.value || "0");
            input.value = isNaN(value) ? 1 : value + 1;
        });

        decrementBtn.addEventListener('click', () => {
            let value = parseInt(input.value || "0");
            if (!isNaN(value) && value > 1) {
                input.value = value - 1;
            } else {
                input.value = 1;
            }
        });

        // Add input validation for quantity field
        input.addEventListener('input', function () {
            const value = this.value.trim();
            if (value === '' || isNaN(parseInt(value)) || parseInt(value) < 1) {
                this.value = 1;
            }
        });
    }

    // Update consumable item indexes after removal
    function updateConsumableItemIndexes() {
        const items = document.querySelectorAll('.consumable-item');
        items.forEach((item, index) => {
            // Update select name and id
            const select = item.querySelector('select');
            if (select) {
                select.name = `consumable[${index}][name]`;
                select.id = `consumable-select-${index}`;
            }

            // Update quantity input name
            const qtyInput = item.querySelector('.quantity-needed');
            if (qtyInput) {
                qtyInput.name = `consumable[${index}][quantity_needed]`;
            }

            // Update label text
            const label = item.querySelector('label');
            if (label) {
                label.innerHTML = `Consumable Name [${index + 1}] <span class="text-red-500">*</span>`;
            }

            item.setAttribute('data-index', index);
        });

        itemCount = items.length;
    }
}

// Form validation
function initializeFormValidation() {
    const form = document.getElementById('productForm');
    if (!form) {
        console.warn('Product form not found');
        return;
    }

    form.addEventListener('submit', function (event) {
        let hasError = false;

        // Validate product image
        if (!validateProductImage()) {
            hasError = true;
        }

        // Validate consumable selections
        if (!validateConsumableSelections()) {
            hasError = true;
        }

        if (hasError) {
            event.preventDefault(); // Prevent form submission
            return false;
        } else {
            // Update UI for form submission
            const submitButton = document.getElementById('submitButton');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            }
        }
    });

    // Validate product image
    function validateProductImage() {
        const imageInput = document.getElementById('productImage');
        if (!imageInput) return true;

        if (!imageInput.files || imageInput.files.length === 0) {
            displayErrorMessage(imageInput, 'imageError', 'Please select a product image');
            imageInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        } else {
            const imageError = document.getElementById('imageError');
            if (imageError) imageError.remove();
            return true;
        }
    }

    // Validate all consumable selections
    function validateConsumableSelections() {
        const consumableItems = document.querySelectorAll('.consumable-item');
        let isValid = true;
        let firstInvalidSelect = null;

        // Skip validation entirely if checkbox is checked
        const isDisabled = document.getElementById('product-consumable-checkbox').checked;
        if (isDisabled) {
            return true;
        }

        consumableItems.forEach((item) => {
            const select = item.querySelector('select[name^="consumable"][name$="[name]"]');
            if (!select || select.disabled) return;

            const errorElement = item.querySelector('.select-error');
            const hsSelectButton = item.querySelector('[data-hs-select] button');

            if (!select.value) {
                isValid = false;

                if (errorElement) {
                    errorElement.style.display = 'block';
                }

                if (hsSelectButton) {
                    hsSelectButton.classList.add('border-red-500');
                }

                if (!firstInvalidSelect) {
                    firstInvalidSelect = hsSelectButton || select;
                }
            } else {
                if (errorElement) {
                    errorElement.style.display = 'none';
                }

                if (hsSelectButton) {
                    hsSelectButton.classList.remove('border-red-500');
                }
            }
        });

        if (firstInvalidSelect) {
            firstInvalidSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    }
}

// Helper to display error messages consistently
function displayErrorMessage(element, errorId, message) {
    // Remove existing error first if it exists
    const existingError = document.getElementById(errorId);
    if (existingError) {
        existingError.remove();
    }

    // Create and append error message
    const errorDiv = document.createElement('div');
    errorDiv.id = errorId;
    errorDiv.className = 'text-red-500 text-sm mt-1';
    errorDiv.textContent = message;

    if (element.nextElementSibling) {
        element.parentNode.insertBefore(errorDiv, element.nextElementSibling);
    } else {
        element.parentNode.appendChild(errorDiv);
    }
}

// Validate consumable selection
function validateConsumableSelection(selectElement) {
    if (!selectElement) return false;

    // Find the consumable item container (parent of everything)
    const itemContainer = selectElement.closest('.consumable-item');
    if (!itemContainer) return false;

    // Directly find the error element by class name
    const errorElement = itemContainer.querySelector('.select-error');

    // Find the HSSelect button 
    const button = itemContainer.querySelector('[data-hs-select] button');

    if (!errorElement || !button) return false;

    if (!selectElement.value) {
        // Show error message when select is empty
        errorElement.style.display = 'block';
        button.classList.add('border-red-500');
        return false;
    } else {
        // Hide error message when select has a value
        errorElement.style.display = 'none';
        button.classList.remove('border-red-500');
        return true;
    }
}

// Attach validation events to a consumable item
function attachValidationEvents(item) {
    const select = item.querySelector('select');
    if (!select) return;

    // Add change event to the actual select
    select.addEventListener('change', function () {
        validateConsumableSelection(select);
    });

    // Try attaching to the custom HSSelect button immediately if it exists
    let customSelectBtn = item.querySelector('[data-hs-select] button');
    if (customSelectBtn) {
        customSelectBtn.addEventListener('click', function () {
            validateConsumableSelection(select);
        });
    } else {
        // Find the custom select button once HSSelect is initialized
        setTimeout(() => {
            customSelectBtn = item.querySelector('[data-hs-select] button');
            if (customSelectBtn) {
                customSelectBtn.addEventListener('click', function () {
                    validateConsumableSelection(select);
                });
            }
        }, 500);
    }

    // Create and attach a MutationObserver to detect when HSSelect updates the DOM
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.type === 'attributes' && (mutation.attributeName === 'value' || mutation.attributeName === 'class')) {
                validateConsumableSelection(select);
            }
        });
    });

    observer.observe(select, { attributes: true });
}

// Initialize HSSelect validation for all select elements
function initializeHSSelectValidation() {
    // First attempt immediate initialization - this works if HSSelect loaded quickly
    initializeExistingSelects();

    // Also try after a delay to ensure HSSelect has fully initialized custom controls
    setTimeout(initializeExistingSelects, 500);

    function initializeExistingSelects() {
        document.querySelectorAll('select[name^="consumable"][name$="[name]"]').forEach(select => {
            const item = select.closest('.consumable-item');
            if (item) {
                attachValidationEvents(item);

                // Make sure the original select element has proper display style
                select.style.opacity = '0';
                select.style.position = 'absolute';
                select.style.pointerEvents = 'none';
                select.classList.remove('hidden');
            }
        });
    }
}

// HSSelect clear function (moved from window.onload)
document.addEventListener('DOMContentLoaded', function () {
    const clearBtn = document.querySelector('#multiple-with-conditional-counter-remote-data-trigger-clear');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            try {
                const select = HSSelect.getInstance('#multiple-with-conditional-counter-remote-data-select', true);
                if (select && select.element && typeof select.element.setValue === 'function') {
                    select.element.setValue([]);
                }
            } catch (error) {
                console.error('Error clearing HSSelect:', error);
            }
        });
    }
});

function initializeConsumableCheckboxToggle() {
    const checkbox = document.getElementById('product-consumable-checkbox');
    const consumableSection = document.querySelector('#consumable-items-container');
    const noConsumableMsg = document.querySelector('[data-no-consumables]');
    const inputsToDisable = consumableSection.querySelectorAll('select, input, button');

    // ✅ Get PHP-passed value from data attribute
    const isConsumableListEmpty = consumableSection.dataset.isEmpty === 'true';

    function toggleConsumableSection(disabled) {
        consumableSection.classList.toggle('opacity-50', disabled);
        consumableSection.classList.toggle('pointer-events-none', disabled);

        inputsToDisable.forEach(el => {
            if (disabled) el.setAttribute('disabled', 'disabled');
            else el.removeAttribute('disabled');
        });

        if (disabled) {
            if (noConsumableMsg) noConsumableMsg.classList.add('hidden');
        } else {
            if (noConsumableMsg) {
                if (isConsumableListEmpty) {
                    noConsumableMsg.classList.remove('hidden');
                } else {
                    noConsumableMsg.classList.add('hidden');
                }
            }
        }
    }

    checkbox.addEventListener('change', () => {
        toggleConsumableSection(checkbox.checked);
    });

    toggleConsumableSection(checkbox.checked); // Initial run
}

function initializeAddConsumableItemButton() {
    const checkbox = document.getElementById('product-consumable-checkbox');
    const addButton = document.getElementById('add-consumable-item');

    // Get initial isConsumableListEmpty from the button's data attribute
    const isConsumableListEmpty = addButton.dataset.isEmpty === 'true';

    function updateAddButtonState() {
        const shouldDisable = isConsumableListEmpty || checkbox.checked;

        if (shouldDisable) {
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            addButton.disabled = false;
            addButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    checkbox.addEventListener('change', updateAddButtonState);

    updateAddButtonState(); // Run on load
}