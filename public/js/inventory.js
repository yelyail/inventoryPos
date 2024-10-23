function openSerialModal(element) {
    const productId = element.getAttribute('data-product-id');
    const productName = element.getAttribute('data-product-name');
    const serialNumbers = element.getAttribute('data-serial').split(', ');
    const serialList = document.getElementById('serialList');

    // Update modal content
    document.getElementById('addSerialProductId').value = productId;
    serialList.innerHTML = ''; 
    document.getElementById('addSerialProductName').innerText = productName; 

    if (serialNumbers && serialNumbers[0] !== '') { 
        serialNumbers.forEach(serial => {
            const listItem = document.createElement('li');
            listItem.textContent = serial.trim();
            serialList.appendChild(listItem);
        });
    } else {
        const listItem = document.createElement('li');
        listItem.textContent = 'No serial numbers available.';
        serialList.appendChild(listItem);
    }

    // Show the modal
    document.getElementById('serialModal').classList.remove('hidden');
}
function closeSerialModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    } else {
        console.error(`Modal with ID ${modalId} not found`);
    }
}

function openAddSerialModal() {
    const modal = document.getElementById('addSerialModal');
    if (modal) {
        modal.classList.remove('hidden');
    } else {
        console.error("Add Serial Modal not found");
    }
}

function closeAddSerialModal() {
    const modal = document.getElementById('addSerialModal');
    if (modal) {
        modal.classList.add('hidden');
    } else {
        console.error("Add Serial Modal not found");
    }
}


const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function addNewSerial(event) {
    event.preventDefault(); // Prevent the default form submission

    const newSerial = document.getElementById('serial_number').value; 
    const productId = document.getElementById('addSerialProductId').value; 
    const serialNumberList = document.getElementById('serialList'); 

    if (newSerial && productId) {
        fetch('/storeSerial', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Include the CSRF token
            },
            body: JSON.stringify({
                serial_number: newSerial,
                product_id: productId
            })
        })
        .then(response => {
            if (response.status === 419) {
                alert('Session expired. Please refresh the page and try again.');
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const li = document.createElement('li');
                li.textContent = newSerial;
                serialNumberList.appendChild(li);

                alert(data.success);
                document.getElementById('serial_number').value = ''; 
                closeAddSerialModal(); 
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert('Please enter a valid serial number and select a product.');
    }
}


// Product Modal Functions
function openAddProductModal() {
    document.getElementById('staticBackdrop').classList.remove('hidden');
}
function closeAddProductModal() {
    document.getElementById('staticBackdrop').classList.add('hidden');
}

// for pending
function approveProduct(productId, button) {
    if (confirm("Are you sure you want to approve this product?")) {
        fetch(`/admin/approve/${productId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); 
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
}

