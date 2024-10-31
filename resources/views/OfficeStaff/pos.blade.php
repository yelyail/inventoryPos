@extends('OfficeStaff.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div class="main-content-flow flex-1 bg-white-100 mt-13 md:mt-2 md:ml-48 pb-28 md:pb-10">
    <div class="flex lg:flex-row flex-col-reverse shadow-lg">
    <!-- left section -->
        <div class="w-full lg:w-3/5 min-h-screen shadow-lg">
            <!-- header -->
            <div class="w-full p-3">
                <div class="bg-gray border border-black-950">
                    <!-- Header Section with Search Box and Add Button -->
                    <div class="flex justify-between items-center uppercase text-gray-800 rounded-tl-lg rounded-tr-lg p-2">
                        <h1 class="prod_title text-2xl font-bold">Point of Sale</h1>
                    </div>
                </div>
            </div>
          <!-- end header -->
          <!-- categories -->
            <div class="mt-3 relative inline-block px-7 mb-7">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
                <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white text-gray-900 transition duration-150 ease-in-out hover:bg-gray-100" id="category" name="categoryName">
                    <option value="" selected>All Items</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
          <!-- end categories -->
            <div class="grid grid-cols-3 gap-4 px-5 mt-5 overflow-y-auto h-1/2" id="itemsContainer">
                @foreach($products as $product)
                    @if($product->serial_count > 0)
                        <div class="relative px-3 py-3 flex flex-col border border-gray-200 rounded-md h-32 item" data-category="{{ $product->category_id }}">
                            <div class="flex-grow">
                                <div class="font-bold text-gray-800">{{ ucwords(strtolower($product->model_name)) }}</div>
                                <span class="font-light text-sm text-gray-400">{{ $product->brand_name }}</span>
                            </div>
                            <img src="{{ asset("storage/{$product->product_image}") }}" class="absolute top-2 right-2 h-14 w-14 object-cover rounded-md" alt="{{ $product->model_name }}">
                            <div class="flex justify-between items-center mt-auto">
                                <span class="font-bold text-lg text-gray-500">₱ {{ number_format($product->unitPrice, 2) }}</span>
                                <button id="addSerialButton" class="bg-blue-500 text-white px-2 py-1 hover:bg-blue-600 rounded" 
                                    onclick="openPosSerialModal(this)" 
                                    data-product-name="{{ $product->model_name }}" 
                                    data-product-id="{{ $product->product_id }}" 
                                    data-product-image="{{ asset("storage/{$product->product_image}") }}"
                                    data-product-price="₱ {{ number_format($product->unitPrice, 2) }}" 
                                    data-serial="{{ $product->serial_numbers->pluck('serial_number')->implode(', ') }}" 
                                    data-created-at="{{ $product->serial_numbers->pluck('created_at')->implode(', ') }}">
                                    <i class="fa-solid fa-plus"></i>  
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
          <!-- end products -->
        </div>
        <!-- right section -->
        <div class="w-full lg:w-2/5">
            <form id="orderForm">
                @csrf
                <!-- header -->
                <div class="flex flex-row items-center w-full p-4 justify-between px-5">
                    <div class="font-bold text-xl">Order Summary</div>
                </div>
                <div class="px-5 py-4 mt-5 overflow-y-auto h-64"></div>
                    <div class="px-5 mt-5">
                        <div class="py-4 rounded-md shadow-lg">
                        <!-- put a form here hahahaha -->
                            <div class="px-4 flex justify-between">
                                <span class="font-semibold text-sm">Subtotal</span>
                                <span id="subtotalDisplay" class="font-bold">₱ 0.00</span>
                            </div>
                            <div class="px-4 flex justify-between">
                                <span class="font-semibold text-sm">Discount</span>
                                <select id="discountSelect" class="border rounded-md" onchange="updateDiscount()">
                                    <option value="0">None</option>
                                    <option value="0.20">Senior Citizen (20%)</option>
                                    <option value="0.20">PWD (20%)</option>
                                </select>
                                <span id="discountDisplay" class="font-bold">₱ 0.00</span>
                            </div>
                            <div class="px-4 flex justify-between">
                                <span class="font-semibold text-sm">VAT Tax</span> 
                                <span id="vatTaxDisplay" class="font-bold">₱ 0.00</span> 
                            </div>
                            <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                                <span class="font-semibold text-2xl">Total</span>
                                <span id="totalDisplay" class="font-bold text-2xl">₱ 0.00</span>
                            </div>
                        </div>
                    </div>
                    <!-- Payment Section -->
                    <div class="px-5 mt-5">
                        <div class="rounded-md shadow-lg px-4 py-4">
                            <div class="flex flex-row justify-between items-center">
                                <div class="flex flex-row space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" class="invisible-checkbox" name="paymentMethod" id="cashCheckbox" value="cash" />
                                        <span class="bg-white text-blue-500 px-4 py-2 rounded-md hover:bg-blue-100 flex items-center">Cash</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" class="invisible-checkbox" name="paymentMethod" id="gcashCheckbox" value="gcash" />
                                        <span class="bg-white text-blue-500 px-4 py-2 rounded-md hover:bg-blue-100 flex items-center">GCash</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Cash Payment Inputs -->
                            <div id="cashNameDiv" class="mt-4 hidden">
                                <label for="cashName" class="block text-gray-700">Enter Name</label>
                                <input type="text" id="cashName" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full"/>
                            </div>
                            <div id="cashAddressDiv" class="mt-4 hidden">
                                <label for="cashAddress" class="block text-gray-700">Enter Address</label>
                                <input type="text" id="cashAddress" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full"/>
                            </div>
                            <div id="cashAmountDiv" class="mt-4 hidden">
                                <label for="cashAmount" class="block text-gray-700">Enter Cash Amount</label>
                                <input type="number" id="cashAmount" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full"/>
                            </div>
                            <!-- GCash Payment Inputs -->
                            <div id="gcashNameDiv" class="mt-4 hidden">
                                <label for="gcashName" class="block text-gray-700">Enter Name</label>
                                <input type="text" id="gcashName" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full"/>
                            </div>
                            <div id="gcashAddressDiv" class="mt-4 hidden">
                                <label for="gcashAddress" class="block text-gray-700">Enter Address</label>
                                <input type="text" id="gcashAddress" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                            </div>
                            <div id="gcashReferenceDiv" class="mt-4 hidden">
                                <label for="gcashReference" class="block text-gray-700">Reference Number</label>
                                <input type="number" id="gcashReference" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="px-5 mt-5">
                        <button id="confirmPayment"  type="button" onclick="sendToDatabase()" class="confirm px-12 py-4 rounded-md shadow-lg text-center bg-green-400 hover:bg-green-700 text-white font-semibold">
                            Confirm Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- Modal for Serial Numbers -->
<div id="serialModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-labelledby="serialModalLabel">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 h-1/2 overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b">
            <h1 class="text-lg font-semibold" id="serialModalLabel">
                Serial Numbers for <span id="addSerialProductName"></span>
            </h1>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeSerialModal('serialModal')" aria-label="Close modal">
                &times;
            </button>
        </div>
        <div class="p-4">
            <div class="relative w-full mb-5">
                <div class="flex items-center">
                    <span class="input-group-text p-2" id="basic-addon1">
                        <i class="fas fa-sharp fa-magnifying-glass pr-0 md:pr-3"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control p-2 rounded-l-md" placeholder="Search..." aria-label="Search">
                    <button class="btn custom-btn p-2 rounded-r-md" type="button" onclick="filterPosTable()">Search</button>
                </div>
            </div>
            <input type="hidden" id="addSerialProductId" value="">
            <table class="min-w-full border-collapse border border-gray-100">
                <thead>
                    <tr>
                        <th class="border border-gray-300 p-2 text-left">Serial #</th>
                        <th class="border border-gray-300 p-2 text-left">Date Added</th>
                        <th class="border border-gray-300 p-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody id="serialCreatedAtList" class="divide-y divide-gray-200">
                </tbody>
            </table>
            <button id="addorderButton" class="mt-4 bg-blue-400 text-white px-4 py-2 rounded">Add Order</button> 
        </div>
    </div>
</div>
@endsection
