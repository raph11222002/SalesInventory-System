document.addEventListener('DOMContentLoaded', function () {
    let itemCount = document.querySelectorAll('.consumable-item').length;

    document.getElementById('add-consumable-item').addEventListener('click', function () {
        const container = document.getElementById('consumable-items-container');
        const currentIndex = itemCount;

        const newItem = document.createElement('div');
        newItem.className = 'flex flex-col md:flex-row items-start md:items-end mb-4 gap-4 consumable-item';
        newItem.innerHTML = `
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-300 mb-1">Consumable Name [${currentIndex + 1}]
                <span class="text-red-500">*</span></label>
            <input type="text" name="consumable[${currentIndex}][name]" autocomplete="off" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <button type="button"
            class="remove-item bg-red-500 hover:bg-red-600 px-3 py-2.5 rounded-md text-sm mt-2 md:mt-0 border-none hover:opacity-75">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;

        container.appendChild(newItem);
        itemCount++;

        // Bind remove and counter events to new item
        newItem.querySelector('.remove-item').addEventListener('click', function () {
            container.removeChild(newItem);
            updateConsumableItemIndexes();
        });

        attachCounterEvents(newItem); // Rebind counter logic to this new item
    });

    function attachCounterEvents(item) {
        const decrementBtn = item.querySelector('.decrement');
        const incrementBtn = item.querySelector('.increment');

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
            const nameInput = item.querySelector('input[name^="consumable_items"][name$="[name]"]');

            if (nameInput) nameInput.name = `consumable_items[${index}][name]`;
        });
        itemCount = items.length;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Open the modal when the "Add Stock" button is clicked
    const openModalButton = document.getElementById('openModalButton');
    const modal = document.getElementById('stockModal');
    const closeModalButton = document.getElementById('closeModalButton');
    const consumableIdInput = document.getElementById('consumable-id');
    const form = document.getElementById('addStockForm');

    // When the "Add Stock" button is clicked
    openModalButton.addEventListener('click', function (e) {
        e.preventDefault();

        // Get the consumable ID from the button data attribute
        const consumableId = openModalButton.getAttribute('data-consumable-id');

        // Set the consumable ID to the hidden input
        consumableIdInput.value = consumableId;

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

