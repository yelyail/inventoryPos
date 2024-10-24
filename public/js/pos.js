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

// calculating
let subtotal = 0; // Initialize subtotal globally

function addToOrderSummary(productName, serialValue, productId, productImage, productPrice) {
    const orderList = document.querySelector('.px-5.py-4.mt-5.overflow-y-auto.h-64');

    const orderItem = document.createElement('div');
    orderItem.className = 'flex flex-row justify-between items-center mb-4 order-item';
    
    const priceNumber = parseFloat(productPrice.replace(/[^0-9.-]+/g, ""));
    subtotal += priceNumber; 
    
    orderItem.innerHTML = `
        <div class="flex flex-row items-center w-1/4">
            <img src="${productImage}" class="w-10 h-10" alt="${productId}">
            <span class="ml-4 font-semibold text-sm">${productName}</span>
        </div>
        <div class="w-32 flex justify-between">
            <span class="px-2 py-1 font-normal item-serial">${serialValue}</span>
        </div>
        <div class="w-32 flex justify-between">
            <span class="px-3 py-1 font-semibold item-price">₱ ${priceNumber.toFixed(2)}</span>
        </div>
        <div class="font-normal text-lg w-1/4 text-center">${productPrice}</div>
    `;

    orderList.appendChild(orderItem); 
    updateDiscount();
    updateSummary(); 
}
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

    document.getElementById('discountDisplay').textContent =formatCurrency(discountAmount); 
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

    const total = subtotal - discount + vatTax; 
    
    
    
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

// confirming payment
// document.getElementById('orderForm').addEventListener('submit', function(event) {
//     event.preventDefault();

//     // Gather order details
//     const orderData = {
//         paymentMethod: document.getElementById('gcashCheckbox').checked ? 'GCash' : 'Cash',
//         gcash: {
//             name: document.getElementById('gcashName').value,
//             address: document.getElementById('gcashAddress').value,
//             reference: document.getElementById('gcashReference').value,
//         },
//         cash: {
//             amount: document.getElementById('cashAmount').value,
//             address: document.getElementById('cashAddress').value,
//             name: document.getElementById('cashName').value,
//         },
//         items: [],
//         subtotal: subtotal,
//         discount: parseFloat(document.getElementById('discountDisplay').textContent.replace(/[^0-9.-]+/g, "")) || 0,
//         total: parseFloat(document.getElementById('totalDisplay').textContent.replace(/[^0-9.-]+/g, "")) || 0,
//     };

//     // Gather items from the order summary
//     const orderItems = document.querySelectorAll('.order-item');
//     orderItems.forEach(item => {
//         const productName = item.querySelector('span').textContent;
//         const itemPriceText = item.querySelector('.item-price').textContent;
//         const itemPrice = parseFloat(itemPriceText.replace(/[^0-9.-]+/g, ""));
        
//         orderData.items.push({ name: productName, price: itemPrice });
//     });

//     // Send the data to the server
//     fetch('/storeOrder', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': csrfToken,
//         },
//         body: JSON.stringify(orderData),
//     })
//     .then(response => {
//         // Check if the response is okay (status in the range 200-299)
//         if (!response.ok) {
//             throw new Error('Network response was not ok');
//         }
//         return response.json(); // Attempt to parse JSON
//     })
//     .then(data => {
//         console.log('Success:', data);
//     })
//     .catch((error) => {
//         console.error('Error:', error);
//     });
// });
    







