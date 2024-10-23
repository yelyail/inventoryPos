// gcash checkbox
document.getElementById('gcashCheckbox').addEventListener('change', function() {
    const gcashNameDiv = document.getElementById('gcashNameDiv');
    const gcashAddressDiv = document.getElementById('gcashAddressDiv');
    const gcashReferenceDiv = document.getElementById('gcashReferenceDiv');
    const cashAmountDiv = document.getElementById('cashAmountDiv');
    const cashAddressDiv = document.getElementById('cashAddressDiv');
    const cashNameDiv = document.getElementById('cashNameDiv');
    const cashCheckbox = document.getElementById('cashCheckbox');

    if (this.checked) {
        // Uncheck cash checkbox if GCash is checked
        cashCheckbox.checked = false;

        // Hide cash fields
        cashAmountDiv.classList.add('hidden');
        cashAddressDiv.classList.add('hidden');
        cashNameDiv.classList.add('hidden');
        document.getElementById('cashAmount').value = '';
        document.getElementById('cashName').value = '';
        document.getElementById('cashAddress').value = '';

        // Show GCash fields
        gcashNameDiv.classList.remove('hidden');
        gcashAddressDiv.classList.remove('hidden');
        gcashReferenceDiv.classList.remove('hidden');
    } else {
        // Hide GCash fields when unchecked
        gcashNameDiv.classList.add('hidden');
        gcashAddressDiv.classList.add('hidden');
        gcashReferenceDiv.classList.add('hidden');
        document.getElementById('gcashName').value = '';
        document.getElementById('gcashAddress').value = '';
        document.getElementById('gcashReference').value = '';
    }
});

// cash checkbox
document.getElementById('cashCheckbox').addEventListener('change', function() {
    const cashAmountDiv = document.getElementById('cashAmountDiv');
    const cashAddressDiv = document.getElementById('cashAddressDiv');
    const cashNameDiv = document.getElementById('cashNameDiv');
    const gcashCheckbox = document.getElementById('gcashCheckbox');
    const gcashNameDiv = document.getElementById('gcashNameDiv');
    const gcashAddressDiv = document.getElementById('gcashAddressDiv');
    const gcashReferenceDiv = document.getElementById('gcashReferenceDiv');

    if (this.checked) {
        // Uncheck GCash checkbox if cash is checked
        gcashCheckbox.checked = false;

        // Hide GCash fields
        gcashNameDiv.classList.add('hidden');
        gcashAddressDiv.classList.add('hidden');
        gcashReferenceDiv.classList.add('hidden');
        document.getElementById('gcashName').value = '';
        document.getElementById('gcashAddress').value = '';
        document.getElementById('gcashReference').value = '';

        // Show cash fields
        cashAmountDiv.classList.remove('hidden');
        cashAddressDiv.classList.remove('hidden');
        cashNameDiv.classList.remove('hidden');
    } else {
        // Hide cash fields when unchecked
        cashAmountDiv.classList.add('hidden');
        cashAddressDiv.classList.add('hidden');
        cashNameDiv.classList.add('hidden');
        document.getElementById('cashAmount').value = '';
        document.getElementById('cashName').value = '';
        document.getElementById('cashAddress').value = '';
    }
});
// Function to close the modal
function closeSerialModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function filterPosTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('serialCreatedAtList');
    const rows = table.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        const serialCell = rows[i].getElementsByTagName('td')[0]; 
        if (serialCell) {
            const txtValue = serialCell.textContent || serialCell.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                rows[i].style.display = ''; 
            } else {
                rows[i].style.display = 'none'; 
            }
        }
    }
}
function openPosSerialModal(button) {
    const productName = button.getAttribute('data-product-name');
    const productId = button.getAttribute('data-product-id');
    const serialNumbers = button.getAttribute('data-serial').split(', '); // Convert to array

    document.getElementById('addSerialProductName').textContent = productName;
    document.getElementById('addSerialProductId').value = productId;

    const serialList = document.getElementById('serialCreatedAtList');
    serialList.innerHTML = ''; // Clear existing entries
    serialNumbers.forEach((serial) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="border border-gray-300 p-2">${serial}</td>
            <td class="border border-gray-300 p-2">${new Date().toLocaleDateString()}</td>
            <td class="border border-gray-300 p-2">
                <input type="checkbox" name="serial" value="${serial}" />
            </td>
        `;
        serialList.appendChild(row);
    });

    // Ensure the Add button works when clicked
    document.getElementById('addSerialButton').onclick = function () {
        const selectedSerials = document.querySelectorAll('input[name="serial"]:checked');
        if (selectedSerials.length > 0) {
            selectedSerials.forEach(selectedSerial => {
                const serialValue = selectedSerial.value;
                // Add to the Order Summary
                addToOrderSummary(productName, serialValue, productId);
            });
            closeSerialModal('serialModal');
        } else {
            alert('Please select at least one serial number.');
        }
    };

    document.getElementById('serialModal').classList.remove('hidden');
}


// Close modal function (implement if you haven't)
function closeSerialModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
// Function to update quantity
function updateQuantity(button, change) {
    const quantityElement = button.parentElement.querySelector('.font-semibold');
    let quantity = parseInt(quantityElement.textContent);
    quantity = Math.max(1, quantity + change); // Prevent quantity from going below 1
    quantityElement.textContent = quantity;
}

