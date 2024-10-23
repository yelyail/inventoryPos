@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')

<div id="main" class="main-content-flow flex-1 bg-white-100 mt-15 md:mt-4 md:ml-60 pb-30 md:pb-20">
  <div class="flex flex-row flex-wrap flex-grow mt-1">
    <div class="w-full p-2">
      <div class="bg-gray border border-black-950">
        <div class="flex justify-between items-center uppercase text-gray-800 rounded-tl-lg rounded-tr-lg p-2">
          <h1 class="font-bold uppercase text-gray-600 text-3xl">Inventory</h1>
          <div class="flex space-x-2">
            <div class="flex-grow">
                <div class="relative w-full">
                    <div class="flex items-center border border-gray-300 rounded-md">
                        <span class="input-group-text p-2" id="basic-addon1">
                            <i class="fas fa-sharp fa-magnifying-glass pr-0 md:pr-3:"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control p-2 rounded-l-md" placeholder="Search..." aria-label="Search">
                        <button class="btn custom-btn p-2 rounded-r-md" type="button" onclick="filterTable()">Search</button>
                    </div>
                </div>
            </div>
            <button onclick="openAddProductModal()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-800">Add Product</button>
          </div>
        </div>
        <div class="overflow-x-auto w-full">
          <table class="table w-full p-5 text-gray-700 custom-table">
            <thead class="text-1xl">
              <tr>
                <th class="w-40">Product Image</th>
                <th class="w-40">Category Name</th>
                <th class="w-40">Brand Name</th>
                <th class="w-40">Model Name</th>
                <th class="w-40">Supplier Name</th>
                <th class="w-40">Current Stocks</th>
                <th class="w-40">Price</th>
                <th class="w-40">Date Added</th>
                <th class="w-40">Warranty Expired</th>
                <th class="w-40">Unit</th>
                <th class="w-40 text-center">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="cursor-pointer hover:opacity-80" 
                    data-product-id="{{ $product->product_id }}" 
                    data-product-name="{{ $product->model_name }}" 
                    data-serial="{{ isset($product->serial_numbers) ? implode(', ', $product->serial_numbers) : '' }}" 
                    onclick="openSerialModal(this)">
                        <td>
                            <img src="{{ asset("storage/{$product->product_image}") }}" 
                                alt="{{ $product->model_name }}" 
                                style="width: 200px; height: 100px;">
                        </td>
                        <td>{{ ucwords(strtolower($product->category_name ?? 'N/A')) }}</td>
                        <td>{{ ucwords(strtolower($product->brand_name ?? 'N/A')) }}</td>
                        <td>{{ ucwords(strtolower($product->model_name ?? 'N/A')) }}</td>
                        <td>{{ ucwords(strtolower($product->supplier_name ?? 'N/A')) }}</td>
                        <td class="text-center">{{ $product->serial_count ?? '0' }}</td> 
                        <td>₱{{ number_format($product->unitPrice, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($product->date_added)->format('Y-m-d') }}</td>
                        <td>
                            @if ($product->warranty_expired)
                                {{ \Carbon\Carbon::parse($product->warranty_expired)->format('Y-m-d') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $product->unit ?? 'N/A' }}</td>
                        <td class="text-center">
                            <div class="flex space-x-2">
                                <button class="bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded flex items-center" onclick="event.stopPropagation();">
                                    <i class="fa-regular fa-pen-to-square mr-2"></i>Edit
                                </button>
                                <button class="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded flex items-center" onclick="event.stopPropagation();">
                                    <i class="fa-solid fa-box-archive mr-2"></i>Archive
                                </button>   
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Adding New Product -->
<div id="staticBackdrop" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-labelledby="staticBackdropLabel" >
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
        <div class="flex justify-between items-center p-4 border-b">
            <h1 class="text-lg font-semibold" id="staticBackdropLabel">Add Product</h1>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeAddProductModal()">
                &times; <!-- Close button -->
            </button>
        </div>
        <div class="p-4">
        <form id="inventoryForm" action="{{ route('storeInventory') }}" method="POST" enctype="multipart/form-data">
          @csrf
                <div class="mb-4">
                    <label for="categoryName" class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="categoryName" name="category_name" placeholder="Enter category name" required>
                </div>
                <div class="mb-4">
                    <label for="brand_name" class="block text-sm font-medium text-gray-700">Brand Name</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="brand_name" name="brand_name" placeholder="Enter Brand Name" required>
                </div>
                <div class="mb-4">
                    <label for="product_name" class="block text-sm font-medium text-gray-700">Model Name</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="product_name" name="product_name" placeholder="Enter Model Name" required>
                </div>
                <div class="mb-4">
                    <label for="typeOfUnit" class="block text-sm font-medium text-gray-700">Units</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="typeOfUnit" name="typeOfUnit" placeholder="Enter Unit/s" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="unitPrice" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="unitPrice" name="unitPrice" placeholder="Enter price" required>
                    </div>
                    <div>
                        <label for="added_date" class="block text-sm font-medium text-gray-700">Date Added</label>
                        <input type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="added_date" name="added_date" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="warranty_supplier" class="block text-sm font-medium text-gray-700">Warranty</label>
                        <input type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="warranty_supplier" name="warranty_supplier" placeholder="Enter warranty period" required>
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
                        @foreach($suppliers as $supplier)
                          <option value="{{ $supplier->supplier_ID }}">{{ $supplier->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="product_image" name="product_image" required>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" class="bg-gray-300 text-black px-4 py-2 rounded-md mr-2" onclick="closeAddProductModal()">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Serial Numbers -->=
<div id="serialModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-labelledby="serialModalLabel">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
        <div class="flex justify-between items-center p-4 border-b">
            <h1 class="text-lg font-semibold" id="serialModalLabel">Serial Numbers for <span id="addSerialProductName"></span></h1>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeSerialModal('serialModal')">
                &times;
            </button>
        </div>
        <div class="p-4">
            <ul id="serialList" class="mt-2 list-disc list-inside"></ul>
            <div class="mt-4 flex justify-end">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700" onclick="openAddSerialModal()">Add New Serial</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Adding New Serial Number -->
<div id="addSerialModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-labelledby="addSerialModalLabel">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
        <div class="flex justify-between items-center p-4 border-b">
            <h1 class="text-lg font-semibold" id="addSerialModalLabel">Add Serial Number for <span id="addSerialProductName"></span></h1>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeAddSerialModal()">
                &times; 
            </button>
        </div>
        <form id="addSerialForm" onsubmit="addNewSerial(event)">
            <div class="p-4">
                <input type="hidden" name="product_id" id="addSerialProductId">
                <div class="mb-4">
                    <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="serial_number" name="serial_number" placeholder="Enter Serial Number" required>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add Serial</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection