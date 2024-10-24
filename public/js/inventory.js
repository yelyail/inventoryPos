function openSerialModal(element) {
    const productName = element.getAttribute('data-product-name');
    const productId = element.getAttribute('data-product-id'); // Assuming this is where you get the product ID
    const serialData = element.getAttribute('data-serial');
    const createdAtData = element.getAttribute('data-created-at');

    document.getElementById('addSerialProductId').value = productId; // Set the hidden input value

    const serialNumbers = serialData ? serialData.split(', ') : [];
    const createdAtDates = createdAtData ? createdAtData.split(', ') : [];

    document.getElementById('addSerialProductName').textContent = productName;
    const serialCreatedAtList = document.getElementById('serialCreatedAtList');
    serialCreatedAtList.innerHTML = ''; // Clear previous content

    serialNumbers.forEach((serial, index) => {
        const createdAt = createdAtDates[index] || 'N/A';
        const row = document.createElement('tr');

        const serialCell = document.createElement('td');
        serialCell.classList.add('border', 'border-gray-200', 'p-2');
        serialCell.textContent = serial;

        const createdAtCell = document.createElement('td');
        createdAtCell.classList.add('border', 'border-gray-300', 'p-2');
        createdAtCell.textContent = createdAt;
        row.appendChild(serialCell);
        row.appendChild(createdAtCell);

        serialCreatedAtList.appendChild(row);
    });

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

    const newSerial = document.getElementById('serial_number').value.trim(); 
    const productId = document.getElementById('addSerialProductId').value.trim(); 
    const serialNumberList = document.getElementById('serialCreatedAtList'); 

    if (newSerial && productId) {
        fetch('/storeSerial', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken 
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
function userArchive(user_id, button) {
    if (confirm("Are you sure you want to archive this user?")) {
        fetch(`/admin/userarchive/${user_id}`, {
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
function supplierArchive(supplier_ID, button) {
    if (confirm("Are you sure you want to archive this supplier?")) {
        fetch(`/admin/supplierArchive/${supplier_ID}`, {
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
function inventoryArchive(product_id, button) {
    if (confirm("Are you sure you want to archive this product?")) {
        fetch(`/admin/inventoryArchive/${product_id}`, {
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
function filterTable() {
    let input = document.getElementById('searchInput');
    let filter = input.value.toLowerCase();
    let table = document.querySelector('.custom-table');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) { 
        let td = tr[i].getElementsByTagName('td');
        let found = false;
        for (let j = 0; j < td.length; j++) {
            if (td[j] && td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        tr[i].style.display = found ? '' : 'none';
    }
}
