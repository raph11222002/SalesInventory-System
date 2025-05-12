

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

