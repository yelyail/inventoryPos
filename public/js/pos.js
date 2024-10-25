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

    // Use `let` instead of `const` for mutable variables
    if (productMap[productId]) {
        // Increment the existing quantity
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
document.getElementById('confirmPayment').addEventListener('click', function() {
    const selectedPaymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

    if (!selectedPaymentMethod) {
        alert('Please select a payment method.');
        return;
    }

    const paymentMethod = selectedPaymentMethod.value;
    const orderSummary = [];

    console.log('Global total:', total);  // Debug log for global total
    console.log('Product Map:', productMap);  // Debug log for product map

    // Collect product data from productMap
    if (productMap && Object.keys(productMap).length > 0) {
        for (const [productId, product] of Object.entries(productMap)) {
            orderSummary.push({
                productId: productId,
                productName: product.productName,
                quantity: product.quantity,
                pricePerItem: product.priceNumber,
                totalPrice: (product.priceNumber * product.quantity).toFixed(2),
                serialNumbers: product.serialArray,
            });
        }
    } else {
        alert('Product map is empty. Please add products to your cart.');
        return;
    }

    console.log('Order Summary:', orderSummary);

    // Check if totalAmount is valid
    const totalAmount = total;
    if (isNaN(totalAmount) || totalAmount <= 0) {
        alert('Total amount is invalid. Please check your order.');
        return;
    }

    const formData = {
        orderSummary: orderSummary,
        paymentMethod: paymentMethod,
        totalAmount: totalAmount,
    };

    console.log("Form Data to send:", formData);

    const orderList = document.querySelector('.px-5.py-4.mt-5.overflow-y-auto.h-64');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/storeOrder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(formData),
    })
    .then(response => {
        console.log('Server Response:', response);  // Log the raw server response
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`Server error: ${response.status} ${response.statusText}. Response: ${text}`);
            });
        }
        return response.json();  // Parse the JSON response
    })
    .then(jsonData => {
        console.log('Parsed server response:', jsonData);
        if (jsonData.success) {
            alert('Payment confirmed and order placed successfully!');
            productMap = {};  // Reset product map
            orderList.innerHTML = '';  // Clear order list display
        } else {
            alert('An error occurred during payment confirmation: ' + jsonData.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(`An error occurred: ${error.message}. Please try again.`);
    });
});




