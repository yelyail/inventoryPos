@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div id="main" class="main-content flex-1 bg-white-100 mt-13 md:mt-2 md:ml-48 pb-28 md:pb-10">
  <div class="flex flex-row flex-wrap flex-grow mt-1">
    <div class="w-full p-2">
      <div class="bg-gray border border-black-950">
        <div class="flex justify-between items-center uppercase text-gray-800 rounded-tl-lg rounded-tr-lg p-2">
          <h1 class="font-bold uppercase text-gray-600 text-3xl">Inventory</h1>
          <div class="flex space-x-2">
            <input type="text" class="border border-gray-300 rounded-md p-2" placeholder="Search...">
            <button onclick="openAddProductModal()" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Product</button>
          </div>
        </div>
        <div class="overflow-x-auto w-full">
          <table class="table w-full p-5 text-gray-700">
            <thead class="text-1xl">
              <tr>
                <th class="w-40">Product Image</th>
                <th class="w-40">Category Name</th>
                <th class="w-40">Brand Name</th>
                <th class="w-40">Model Name</th>
                <th class="w-40">Supplier Name</th>
                <th class="w-40">Current Stocks</th>
                <th class="w-40">Price</th>
                <th class="w-40">Updated Stocks</th>
                <th class="w-40">Restock Date</th>
                <th class="w-40">Date Added</th>
                <th class="w-40">Warranty</th>
                <th class="w-40">Unit</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr >
                <td class="cursor-pointer hover:opacity-75" onclick="openSerialModal('Samsung Galaxy S21', ['14242', '13242', '54533'])">
                    <img src="{{ asset('/product_images/1727898641-AK-170.jpg') }}" alt="Product Image" 
                        class="w-30 h-20 ">
                </td>
                <td>Electronics</td>
                <td>Samsung</td>
                <td>Galaxy S21</td>
                <td>Supplier A</td>
                <td class='text-center'>10</td>
                <td class='text-center'>$999</td>
                <td class='text-center'>15</td>
                <td>2024-10-01</td>
                <td>2024-01-01</td>
                <td class='text-center'>1 year</td>
                <td class='text-center'>Unit</td>
                <td><button class="bg-green-500 text-white px-2 py-1 rounded">Edit</button></td>
              </tr>
              <!-- More rows as needed -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Adding New Product -->
<div id="staticBackdrop" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
        <div class="flex justify-between items-center p-4 border-b">
            <h1 class="text-lg font-semibold" id="staticBackdropLabel">Add Product</h1>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeAddProductModal()">
                &times; <!-- Close button -->
            </button>
        </div>
        <div class="p-4">
            <form id="inventoryForm" action="" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="categoryName" class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="categoryName" name="categoryName" placeholder="Enter category name" required>
                </div>
                <div class="mb-4">
                    <label for="productName" class="block text-sm font-medium text-gray-700">Brand Name</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="brandname" name="brandname" placeholder="Enter Brand Name" required>
                </div>
                <div class="mb-4">
                    <label for="productName" class="block text-sm font-medium text-gray-700">Model Name</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="modelName" name="productName" placeholder="Enter Model Name" required>
                </div>
                <div class="mb-4">
                    <label for="stocks" class="block text-sm font-medium text-gray-700">Stocks</label>
                    <input type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="stocks" name="stocks" placeholder="Enter how many stocks" required>
                </div>
                <div class="mb-4">
                    <label for="unit" class="block text-sm font-medium text-gray-700">Units</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="unit" name="unit" placeholder="Enter Unit/s" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="itemPrice" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="itemPrice" name="pricePerUnit" placeholder="Enter price" required>
                    </div>
                    <div>
                        <label for="itemDate" class="block text-sm font-medium text-gray-700">Date Added</label>
                        <input type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="itemDate" name="dateAdded" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="warranty" class="block text-sm font-medium text-gray-700">Warranty</label>
                        <input type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="warrantyPeriod" name="warrantyPeriod" placeholder="Enter warranty period" required>
                    </div>
                    <div>
                        <label for="warrantyUnit" class="block text-sm font-medium text-gray-700">Warranty Units</label>
                        <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="warrantyUnit" name="warrantyUnit" required>
                            <option value="" disabled selected>Warranty Units</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="suppName" class="block text-sm font-medium text-gray-700">Supplier Name</label>
                    <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="suppName" name="supplierName" required>
                        <option value="" disabled selected>Supplier Name</option>
                  
                    </select>
                </div>
                <div class="mb-4">
                    <label for="productImage" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="productImage" name="productImage" accept="image/*" required>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn bg-blue-500 text-white hover:bg-blue-600">Save</button>
                    <button type="button" class="btn bg-red-500 text-white hover:bg-red-600 ml-2" onclick="closeAddProductModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main Modal for Serial Numbers -->
<div id="serialNumberModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
  <div class="modal-box">
    <h3 class="text-lg font-semibold mb-4">Serial Numbers for <span id="productModelName"></span></h3>
    <ul id="serialNumberList" class="list-disc pl-5"></ul>
    <div class="modal-action">
      <button onclick="openAddSerialModal()" class="btn bg-green-500 text-white border-4 border-green-500 hover:bg-green-600 hover:border-green-600">Add Serial</button>
      <button onclick="closeModal()" class="btn bg-red-500 text-white border-4 border-red-500 hover:bg-red-600 hover:border-red-600">Close</button>
    </div>
  </div>
</div>

<!-- Modal for Adding New Serial -->
<div id="addSerialModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
  <div class="modal-box">
    <h3 class="text-lg font-semibold mb-4">Add New Serial for <span id="addSerialProductName"></span></h3>
    <input type="text" id="newSerialInput" class="border border-gray-300 rounded-md p-2 mb-4" placeholder="Enter new serial number">
    <div class="modal-action">
      <button onclick="addNewSerial()" class="btn bg-green-500 text-white border-4 border-green-500 hover:bg-green-600 hover:border-green-600">Add Serial</button>
      <button onclick="closeAddSerialModal()" class="btn bg-red-500 text-white border-4 border-red-500 hover:bg-red-600 hover:border-red-600">Close</button>
    </div>
  </div>
</div>

<script>
function openSerialModal(productModelName, serialNumbers) {
  console.log("Modal opened for:", productModelName, "with serial numbers:", serialNumbers);

    document.getElementById('productModelName').innerText = productModelName;
    const serialNumberList = document.getElementById('serialNumberList');
    serialNumberList.innerHTML = '';

    // Populate the modal with serial numbers
    serialNumbers.forEach(serial => {
        const li = document.createElement('li');
        li.textContent = serial;
        serialNumberList.appendChild(li);
    });
    document.getElementById('serialNumberModal').classList.remove('hidden');
}

function closeModal() {
  document.getElementById('serialNumberModal').classList.add('hidden');
}

function openAddSerialModal() {
    document.getElementById('addSerialModal').classList.remove('hidden');
}

function closeAddSerialModal() {
    document.getElementById('addSerialModal').classList.add('hidden');
}

function addNewSerial() {
    const newSerial = document.getElementById('newSerialInput').value;
    const productModelName = document.getElementById('productModelName').innerText;

    if (newSerial) {
        const li = document.createElement('li');
        li.textContent = newSerial;
        document.getElementById('serialNumberList').appendChild(li);
        closeAddSerialModal(); // Close the add serial modal
    }
}
function openAddProductModal() {
    document.getElementById('staticBackdrop').classList.remove('hidden');
}

function closeAddProductModal() {
    document.getElementById('staticBackdrop').classList.add('hidden');
}
</script>

@endsection
