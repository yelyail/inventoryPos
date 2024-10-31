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
    const productImage = button.getAttribute('data-product-image'); // Get product image
    const productPrice = button.getAttribute('data-product-price'); // Get product price

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

    document.getElementById('addorderButton').onclick = function () {
        const selectedSerials = document.querySelectorAll('input[name="serial"]:checked');
        if (selectedSerials.length > 0) {
            selectedSerials.forEach(selectedSerial => {
                const serialValue = selectedSerial.value;
                addToOrderSummary(productName, serialValue, productId, productImage, productPrice);
            });
            closeSerialModal('serialModal');
        } else {
            alert('Please select at least one serial number.');
        }
    };

    document.getElementById('serialModal').classList.remove('hidden');
}
function closeSerialModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

const productMap = {};
let subtotal = 0;
let total = 0;

function addToOrderSummary(productName, serialValue, productId, productImage, productPrice) {
    const orderList = document.querySelector('.px-5.py-4.mt-5.overflow-y-auto.h-64');  
    const priceNumber = parseFloat(productPrice.replace(/[^0-9.-]+/g, ""));

    const serialArray = serialValue ? serialValue.split(',').map(serial => serial.trim()) : [];
    const quantity = serialArray.length;

    if (productMap[productId]) {
        productMap[productId].quantity += quantity;
        productMap[productId].serialArray.push(...serialArray);
    } else {
        productMap[productId] = {
            productImage,
            productName,
            productPrice,
            priceNumber,
            quantity: quantity, // Ensure this is set correctly
            serialArray
        };
    }

    console.log('Updated productMap:', productMap);

    orderList.innerHTML = ''; 
    subtotal = 0;

    for (const product of Object.values(productMap)) {
        const orderItem = document.createElement('div');
        orderItem.className = 'flex flex-row justify-between items-center mb-4 order-item';
        
        // Calculate total price for the item
        const totalPrice = (product.priceNumber * product.quantity).toFixed(2);
        
        subtotal += parseFloat(totalPrice);

        orderItem.innerHTML = `
            <div class="flex flex-row items-center w-1/4">
                <img src="${product.productImage}" class="w-10 h-10" alt="${productId}">
                <span class="ml-4 font-semibold text-sm">${product.productName}</span>
            </div>
            <div class="w-32 flex justify-between">
                <span class="px-2 py-1 font-semibold item-quantity">Qty: ${product.quantity}</span>
            </div>
            <div class="w-32 flex justify-between">
                <span class="px-2 py-1 font-semibold item-price">${formatCurrency(product.priceNumber)}</span>
            </div>
            <div class="w-32 flex justify-between">
                <span class="px-2 py-1 font-semibold item-total">${formatCurrency(totalPrice)}</span>
            </div>
        `;

        orderList.appendChild(orderItem); 
    }

    console.log('Subtotal:', subtotal);

    updateDiscount();
    updateSummary(); 
}
// Format currency function
const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value);
};

function updateDiscount() {
    const discountSelect = document.getElementById('discountSelect');
    const discountRate = parseFloat(discountSelect.value);
    const discountAmount = subtotal * discountRate;

    document.getElementById('discountDisplay').textContent = formatCurrency(discountAmount);
    updateSummary();
}

function updateSummary() {
    const discount = parseFloat(document.getElementById('discountDisplay').textContent.replace(/[^0-9.-]+/g, "")) || 0; 
    let vatTax = 0; 
    if (discount === 0) { 
        const vatRate = 0.12; 
        vatTax = subtotal * vatRate; 
    }

    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay'); 
    const vatTaxDisplay = document.getElementById('vatTaxDisplay');
    total = subtotal - discount + vatTax; 

    subtotalDisplay.textContent = formatCurrency(subtotal); 
    vatTaxDisplay.textContent = formatCurrency(vatTax); 
    totalDisplay.textContent = formatCurrency(total); 
}
document.querySelectorAll('input[name="paymentMethod"]').forEach((input) => {
        input.addEventListener('change', function() {
            // Hide all payment fields initially
            document.getElementById('cashNameDiv').style.display = 'none';
            document.getElementById('cashAddressDiv').style.display = 'none';
            document.getElementById('cashAmountDiv').style.display = 'none';
            document.getElementById('gcashNameDiv').style.display = 'none';
            document.getElementById('gcashAddressDiv').style.display = 'none';
            document.getElementById('gcashReferenceDiv').style.display = 'none';

            // Show relevant fields based on the selected payment method
            if (this.value === 'cash') {  // Updated to match 'Cash' case
                document.getElementById('cashNameDiv').style.display = 'block';
                document.getElementById('cashAddressDiv').style.display = 'block';
                document.getElementById('cashAmountDiv').style.display = 'block';
            } else if (this.value === 'gcash') {  // Updated to match 'GCash' case
                document.getElementById('gcashNameDiv').style.display = 'block';
                document.getElementById('gcashAddressDiv').style.display = 'block';
                document.getElementById('gcashReferenceDiv').style.display = 'block';
            }
            updatePaymentDetails();
        });
    });

    function updatePaymentDetails() {
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value || 'N/A';
        let paymentName = null;
        let paymentAddress = null;
        let payment = null;

        if (paymentMethod === 'Cash') {
            paymentName = document.getElementById('cashName')?.value.trim() || null;
            paymentAddress = document.getElementById('cashAddress')?.value.trim() || null;
            payment = parseFloat(document.getElementById('cashAmount')?.value.replace(/[^0-9.-]+/g, "")) || 0;
        } else if (paymentMethod === 'GCash') {
            paymentName = document.getElementById('gcashName')?.value.trim() || null;
            paymentAddress = document.getElementById('gcashAddress')?.value.trim() || null;
            payment = parseFloat(document.getElementById('totalDisplay')?.textContent.replace(/[^0-9.-]+/g, "")) || 0; // Assuming total is displayed on the page
        }

        console.log('Payment Details:', {
            paymentMethod,
            paymentName,
            paymentAddress,
            payment
        });
    }

    function sendToDatabase() {
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value || 'N/A';
        const discountAmount = parseFloat(document.getElementById('discountDisplay')?.textContent.replace(/[^0-9.-]+/g, "")) || 0;
        const total = parseFloat(document.getElementById('totalDisplay')?.textContent.replace(/[^0-9.-]+/g, "")) || 0;

        let paymentName = null;
        let paymentAddress = null;
        let payment = null;

        if (paymentMethod === 'cash') {
            paymentName = document.getElementById('cashName')?.value.trim() || null; 
            paymentAddress = document.getElementById('cashAddress')?.value.trim() || null; 
            payment = parseFloat(document.getElementById('cashAmount')?.value.replace(/[^0-9.-]+/g, "")) || 0;
        } else if (paymentMethod === 'gcash') {
            paymentName = document.getElementById('gcashName')?.value.trim() || null; 
            paymentAddress = document.getElementById('gcashAddress')?.value.trim() || null; 
            payment = total; 
        }

        const data = {
            paymentMethod,
            products: Array.from(Object.entries(productMap)).map(([productId, product]) => ({
                productId,
                productName: product.productName,
                quantity: product.quantity,
                serialArray: product.serialArray,
                price: product.priceNumber,
            })),
            paymentName,
            paymentAddress,
            referenceNum: paymentMethod === 'gcash' ? document.getElementById('gcashReference')?.value.trim() || null : null,
            discountAmount,
            total,
        };
        console.log("Data sent to database:", data); // Debugging output

        $.ajax({
            url: '/storeOrder',
            type: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(data),
            success: function(response) {
                if (response.warning) {
                    console.warn(response.warning);
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Confirmed! :)',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Something went wrong. Please try again!';            
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong :(',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
            }
        });
}


document.getElementById('category').addEventListener('change', function() {
    const selectedCategory = this.value;
    const items = document.querySelectorAll('#itemsContainer .item');

    items.forEach(item => {
        const itemCategory = item.getAttribute('data-category');

        if (selectedCategory === "" || itemCategory === selectedCategory) {
            item.style.display = ''; 
        } else {
            item.style.display = 'none'; 
        }
    });
});


