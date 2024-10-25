function openSerialModal(element) {
    const productName = element.getAttribute('data-product-name');
    const productId = element.getAttribute('data-product-id');
    const serialData = element.getAttribute('data-serial');
    const createdAtData = element.getAttribute('data-created-at');

    document.getElementById('addSerialProductId').value = productId; // Set the hidden input value

    const serialNumbers = serialData ? serialData.split(', ') : [];
    const createdAtDates = createdAtData ? createdAtData.split(', ') : [];

    document.getElementById('addSerialProductName').textContent = productName;
    const serialCreatedAtList = document.getElementById('serialCreatedAtList');
    serialCreatedAtList.innerHTML = ''; 

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
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('filter-button').addEventListener('click', filterTable); // Ensure this matches the button ID

    function filterTable() {
        let table = document.querySelector('.cstm-table');
        let searchInput = document.getElementById('searchInput').value.toLowerCase();
        let fromDate = document.getElementById('from_date').value ? new Date(document.getElementById('from_date').value) : null;
        let toDate = document.getElementById('to_date').value ? new Date(document.getElementById('to_date').value) : null;

        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
            let td = tr[i].getElementsByTagName('td');
            let showRow = true;

            // Search filtering
            if ((td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) === -1) && 
                (td[2] && td[2].textContent.toLowerCase().indexOf(searchInput) === -1)) {
                showRow = false;
            }

            if (td[6]) { // Make sure to use the correct index for the date column
                let rowDate = new Date(td[6].textContent.trim()); // Assuming index 6 corresponds to "Date Added"
                if ((fromDate && rowDate < fromDate) || (toDate && rowDate > toDate)) {
                    showRow = false;
                }
            }

            tr[i].style.display = showRow ? '' : 'none'; // Show or hide the row
        }
    }
});
// for updating 
function openEditModal(userId, fullname, username, jobTitle, phoneNumber) {
    document.getElementById('editEmployeeId').value = userId;
    document.getElementById('editEmployeeName').value = fullname;
    document.getElementById('editUserName').value = username;
    document.getElementById('editJobRole').value = jobTitle; 
    document.getElementById('editPhoneNumber').value = phoneNumber;

    // Show the modal
    const modal = document.getElementById('editEmployeeModal');
    modal.classList.remove('hidden'); 
}
function openEditSupplierModal(supplier_id, supplier_name, supplier_phone, supplier_address, supplier_email) {
    document.getElementById('editSupplierId').value = supplier_id;
    document.getElementById('editSupplierName').value = supplier_name;
    document.getElementById('editPhoneNumber').value = supplier_phone;
    document.getElementById('editSupplierAddress').value = supplier_address; 
    document.getElementById('editSupplierEmail').value = supplier_email;

    // Show the modal
    const modal = document.getElementById('editSupplierModal');
    modal.classList.remove('hidden'); 
}

// To close the modal
function closeEditUserModal() {
    const modal = document.getElementById('editEmployeeModal');
    modal.classList.add('hidden');
}
function closeEditSupplierModal() {
    const modal = document.getElementById('editSupplierModal');
    modal.classList.add('hidden');
}
function showTransferAlert(inventory_id) {
    Swal.fire({
        title: "Replacement",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Yes",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Reasons for Requesting a replace',
                input: 'select',
                inputOptions: {
                    'Defective Hardware Components': 'Defective Hardware Components',
                    'Incompatibility with Other Components': 'Incompatibility with Other Components',
                    'Overheating or Performance Degradation': 'Overheating or Performance Degradation',
                    'Others': 'Others'
                },
                inputPlaceholder: 'Select reason',
                confirmButtonText: 'Confirm',
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((reason) => {
                if (reason.isConfirmed && reason.value) {
                    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    $.ajax({
                        url: '/admin/return',
                        method: 'POST',
                        data: {
                            _token: token,
                            ordDet_ID: ordDet_ID,
                            reason: reason.value
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Requesting Repair Confirmed",
                                text: response.success,
                                icon: "success"
                            }).then(() => {
                                document.getElementById('repair-btn-' + ordDet_ID).style.display = 'none';
                                document.getElementById('ongoing-btn-' + ordDet_ID).style.display = 'block';
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error",
                                text: xhr.responseJSON?.error || "There was an issue submitting the request.",
                                icon: "error"
                            });
                        }
                    });
                } else if (!reason.value) {
                    Swal.fire("You must select a reason for the repair request.", "", "warning");
                } else {
                    Swal.fire("Requesting Repair has been canceled", "", "info");
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Requesting Repair has been canceled", "", "info");
        }
    });
}
