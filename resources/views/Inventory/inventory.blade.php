@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div id="main" class="main-content flex-1 bg-white-100 mt-13 md:mt-2 md:ml-48 pb-28 md:pb-10">
  <div class="flex flex-row flex-wrap flex-grow mt-1">
    <div class="w-full p-2">
      <div class="bg-gray border border-black-950">
        <!-- Header Section with Search Box and Add Button -->
        <div class="flex justify-between items-center uppercase text-gray-800  rounded-tl-lg rounded-tr-lg p-2">
          <h1 class="font-bold uppercase text-gray-600 text-3xl">Inventory</h1>
          <div class="flex space-x-2">
            <!-- Search Box -->
            <input type="text" class="border border-gray-300 rounded-md p-2" placeholder="Search...">
            <!-- Add Button -->
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Product</button>
          </div>
        </div>
        <!-- Table Section -->
        <div class="overflow-x-auto w-full">
          <table class="table w-full p-5 text-gray-700">
            <!-- head -->
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
                <th >Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- row 1 -->
              <tr>
                <td class="hover text-center cursor-pointer" onclick="openModal('Samsung Galaxy S21', ['14242', '13242', '54533'])">
                  <img src="{{ asset('/product_images/1727898641-AK-170.jpg') }}" alt="Product Image" class="w-30 h-20">
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
              <!-- row 3 -->
              <tr>
              <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Main Modal for Serial Numbers -->
<dialog id="serialNumberModal" class="modal">
  <div class="modal-box">
    <h3 class="text-lg font-semibold mb-4">Serial Numbers for <span id="productModelName"></span></h3>
    <ul id="serialNumberList" class="list-disc pl-5">
      <!-- Serial numbers will be dynamically inserted here -->
    </ul>
    <div class="modal-action">
      <button onclick="openAddSerialModal()" class="btn bg-green-500 text-white border-4 border-green-500 hover:bg-green-600 hover:border-green-600">Add Serial</button>
      <button onclick="closeModal()" class="btn bg-red-500 text-white border-4 border-red-500 hover:bg-red-600 hover:border-red-600">Close</button>
    </div>
  </div>
</dialog>

<!-- Modal for Adding New Serial -->
<dialog id="addSerialModal" class="modal">
  <div class="modal-box">
    <h3 class="text-lg font-semibold mb-4">Add New Serial for <span id="addSerialProductName"></span></h3>
    <input type="text" id="newSerialInput" class="border border-gray-300 rounded-md p-2 mb-4" placeholder="Enter new serial number">
    <div class="modal-action">
      <button onclick="addNewSerial()" class="btn bg-green-500 text-white border-4 border-green-500 hover:bg-green-600 hover:border-green-600"> Serial</button>
      <button onclick="closeAddSerialModal()" class="btn bg-red-500 text-white border-4 border-red-500 hover:bg-red-600 hover:border-red-600">Close</button>
    </div>
  </div>
</dialog>


<script>
  function openModal(modelName, serialNumbers) {
    document.getElementById('productModelName').textContent = modelName;
    document.getElementById('addSerialProductName').textContent = modelName; // Update for add modal
    const serialNumberList = document.getElementById('serialNumberList');
    serialNumberList.innerHTML = ''; // Clear existing items

    serialNumbers.forEach(serial => {
        const li = document.createElement('li');
        li.textContent = serial;
        serialNumberList.appendChild(li);
    });

    document.getElementById('serialNumberModal').showModal();
}

function closeModal() {
    document.getElementById('serialNumberModal').close();
}

function openAddSerialModal() {
    document.getElementById('addSerialModal').showModal();
}

function closeAddSerialModal() {
    document.getElementById('addSerialModal').close();
}

function addNewSerial() {
    const newSerial = document.getElementById('newSerialInput').value;
    if (newSerial) {
        const li = document.createElement('li');
        li.textContent = newSerial;
        document.getElementById('serialNumberList').appendChild(li);
        document.getElementById('newSerialInput').value = ''; // Clear input
        closeAddSerialModal(); // Close add modal after adding
    } else {
        alert('Please enter a serial number.');
    }
}

  function toggleDD(myDropMenu) {
        document.getElementById(myDropMenu).classList.toggle("invisible");
    }
    window.onclick = function(event) {
        if (!event.target.matches('.drop-button')) {
            var dropdowns = document.getElementsByClassName("dropdownlist");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (!openDropdown.classList.contains('invisible')) {
                    openDropdown.classList.add('invisible');
                }
            }
        }
    }
</script>
<script src="{{ asset('js/alert.js') }}"></script>
@endsection