<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sale System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Point of Sale</h2>

        <div id="successMessage" class="alert alert-success" style="display: none;"></div>

        <div class="row">
            <div class="col-md-8">
                <h3>Available Items</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Serial Number</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody">
                        <!-- Inventory items will be populated here using JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <h3>Checkout</h3>
                <form id="checkoutForm">
                    <div class="mb-3">
                        <label for="sale_date" class="form-label">Sale Date</label>
                        <input type="date" name="sale_date" id="sale_date" class="form-control" required>
                    </div>

                    <input type="hidden" name="items" id="selectedItems">

                    <button type="submit" class="btn btn-primary">Complete Sale</button>
                </form>

                <h4>Selected Items</h4>
                <ul id="selectedItemsList" class="list-group"></ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const availableItems = [
            { serialnum: 'SN001', product_name: 'Product A', category: 'Category 1', brand: 'Brand X' },
            { serialnum: 'SN002', product_name: 'Product B', category: 'Category 2', brand: 'Brand Y' },
            { serialnum: 'SN003', product_name: 'Product C', category: 'Category 1', brand: 'Brand Z' },
            // Add more items as needed
        ];

        // Populate the inventory table
        const inventoryTableBody = document.getElementById('inventoryTableBody');
        availableItems.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="checkbox" class="item-checkbox" value="${item.serialnum}"></td>
                <td>${item.serialnum}</td>
                <td>${item.product_name}</td>
                <td>${item.category}</td>
                <td>${item.brand}</td>
            `;
            inventoryTableBody.appendChild(row);
        });

        // Handle checkout form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(checkbox => checkbox.value);
            const saleDate = document.getElementById('sale_date').value;

            if (selectedItems.length === 0) {
                alert('Please select at least one item.');
                return;
            }

            // Here, you would typically send the data to the server using AJAX
            // For demonstration, we'll just display a success message
            document.getElementById('successMessage').style.display = 'block';
            document.getElementById('successMessage').textContent = 'Items have been sold successfully!';

            // Clear selected items and input
            selectedItemsList.innerHTML = '';
            selectedItemsInput.value = '';
        });

        // Update selected items list
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedItemsList = document.getElementById('selectedItemsList');

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    const li = document.createElement('li');
                    li.textContent = this.value;
                    li.classList.add('list-group-item');
                    selectedItemsList.appendChild(li);
                } else {
                    const items = selectedItemsList.getElementsByTagName('li');
                    for (let i = 0; i < items.length; i++) {
                        if (items[i].textContent === this.value) {
                            selectedItemsList.removeChild(items[i]);
                            break;
                        }
                    }
                }
                updateSelectedItemsInput();
            });
        });

        function updateSelectedItemsInput() {
            const selectedItems = Array.from(selectedItemsList.getElementsByTagName('li')).map(item => item.textContent);
            selectedItemsInput.value = JSON.stringify(selectedItems);
        }
    </script>
</body>
</html>
