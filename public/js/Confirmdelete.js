
function confirmDelete(productId) {
    // Show the delete confirmation modal
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.classList.remove('hidden');

    // Set the product ID to the confirm button
    document.getElementById('confirmDelete').onclick = function () {
        document.getElementById('deleteForm' + productId).submit();
    };

    // Cancel button action
    document.getElementById('cancelDelete').onclick = function () {
        deleteModal.classList.add('hidden');
    };
}
function showACTModal(productId) {
    const activeModal = document.getElementById('activeModal');
    activeModal.classList.remove('hidden');

    const confirmButton = document.getElementById('confirmReactivate');
    const cancelButton = document.getElementById('cancelact');

    if (confirmButton && cancelButton) {
        confirmButton.onclick = function () {
            document.getElementById('reactivateForm' + productId).submit();
            activeModal.classList.add('hidden');
        };

        cancelButton.onclick = function () {
            activeModal.classList.add('hidden');
        };
    } else {
        console.error("Buttons not found in the modal");
    }
}

