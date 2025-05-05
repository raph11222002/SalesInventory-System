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

    let itemCount = document.querySelectorAll('.consumable-item').length;

    document.getElementById('add-consumable-item').addEventListener('click', function () {
        const container = document.getElementById('consumable-items-container');
        const currentIndex = itemCount;

        const newItem = document.createElement('div');
        newItem.className = 'flex flex-col md:flex-row items-start md:items-end mb-4 gap-4 consumable-item';
        newItem.innerHTML = `
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Consumable Name [${currentIndex + 1}]
                                        <span class="text-red-500">*</span>
                                    </label>
    
                                    <div class="flex flex-wrap items-center gap-3">
                                        <!-- Select with Search - Now with appropriate width -->
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
                                                }' class="hidden" required>
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
    
                                            <div
                                                class="absolute bottom-1 start-1/2 -translate-x-1/2 flex items-center text-xs text-gray-900">
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
                                            class="remove-item text-white bg-red-600 hover:bg-red-700 px-4 py-2.5 rounded-md text-sm">
                                            <i class="fas fa-trash mr-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
    `;

        container.appendChild(newItem);

        // Initialize the HS Select component for the newly added dropdown
        const selectElement = newItem.querySelector(`#consumable-select-${currentIndex}`);
        if (selectElement && typeof HSSelect !== 'undefined') {
            HSSelect.autoInit();
        }

        itemCount++;

        // Bind remove and counter events to new item
        newItem.querySelector('.remove-item').addEventListener('click', function () {
            container.removeChild(newItem);
            updateConsumableItemIndexes();
        });

        attachCounterEvents(newItem); // Rebind counter logic to this new item
    });

    // Helper function to generate consumable options from the existing select template
    function generateConsumableOptions() {
        // Get the consumable options from the template or first select
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

        // Fallback if no template is found - this depends on your Blade template actually having rendered
        // these options at least once somewhere in the page
        return '@foreach($consumable_list->groupBy("consumable_name")->sortBy("consumable_name") as $name => $consumables)' +
            '<option value="{{ $consumables->first()->id }}">{{ $name }}</option>' +
            '@endforeach';
    }

    function attachCounterEvents(item) {
        const decrementBtn = item.querySelector('.decrement');
        const incrementBtn = item.querySelector('.increment');
        const input = item.querySelector('.quantity-needed');

        incrementBtn.addEventListener('click', () => {
            let value = parseInt(input.value || "0");
            input.value = value + 1;
        });

        decrementBtn.addEventListener('click', () => {
            let value = parseInt(input.value || "0");
            if (value > 1) input.value = value - 1;
        });
    }

    document.querySelectorAll('.consumable-item').forEach(item => {
        attachCounterEvents(item);
    });

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

    // Function to update consumable item indexes
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
    
            // Update label text if needed
            const label = item.querySelector('label');
            if (label) {
                label.innerHTML = `Consumable Name [${index + 1}] <span class="text-red-500">*</span>`;
            }
    
            item.setAttribute('data-index', index);
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

        if (hasError) {
            e.preventDefault(); // Prevent form submission
        }

        // Continue with submission
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    });

    // Find all HSSelect instances after they've been initialized
    setTimeout(() => {
        const selects = document.querySelectorAll('#consumable-items-container select.selection');
        selects.forEach(select => {
            // Add change event listener to the custom select button
            const customSelectBtn = select.closest('.flex-1').querySelector('[data-hs-select] button');
            if (customSelectBtn) {
                customSelectBtn.addEventListener('click', function() {
                    // Check when dropdown is opened
                    if (select.value === '') {
                        validateConsumableSelection(select);
                    }
                });
            }
        });
    }, 500);
});

window.addEventListener('load', () => {
    (function () {
        const clearBtn = document.querySelector('#multiple-with-conditional-counter-remote-data-trigger-clear');

        clearBtn.addEventListener('click', () => {
            const select = HSSelect.getInstance('#multiple-with-conditional-counter-remote-data-select', true);

            select.element.setValue([]);
        });
    })();
});

// Validate consumable selection
function validateConsumableSelection(selectElement) {
    const errorMsg = selectElement.closest('.flex-1').querySelector('.select-error');
    
    if (!selectElement.value) {
        // Show error message when select is empty
        errorMsg.classList.remove('hidden');
        selectElement.closest('.flex-1').querySelector('[data-hs-select] button').classList.add('border-red-500');
        return false;
    } else {
        // Hide error message when select has a value
        errorMsg.classList.add('hidden');
        selectElement.closest('.flex-1').querySelector('[data-hs-select] button').classList.remove('border-red-500');
        return true;
    }
}

// Form submission validation
document.querySelector('form').addEventListener('submit', function(e) {
    let hasError = false;
    
    // Check all consumable selects
    const selects = document.querySelectorAll('#consumable-items-container select.selection');
    selects.forEach(select => {
        if (!validateConsumableSelection(select)) {
            hasError = true;
        }
    });
    
    // Prevent form submission if there are errors
    if (hasError) {
        e.preventDefault();
    }
});

