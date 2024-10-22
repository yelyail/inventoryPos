@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div id="main" class="main-content-flow flex-1 bg-white-100 mt-13 md:mt-2 md:ml-60 pb-28 md:pb-10">
    <div class="w-full flex flex-col flex-grow mt-1">
        <div class="w-full p-5">
            <div class="bg-gray border border-black-950">
                <!-- Header Section with Search Box and Add Button -->
                <div class="flex justify-between items-center uppercase text-gray-800 rounded-tl-lg rounded-tr-lg p-2">
                    <h1 class="prod_title text-2xl font-bold ">Pending Verification</h1>
                </div>
            </div>
        </div>
        <div class="container mt-7">
            <div class="flex mb-4 items-center">
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
                <div class="md:w-full text-right mt-3 md:mt-0"></div>
            </div>
            <div class="overflow-x-auto">
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
                    <td class='text-center'>1 year</td>
                    <td class='text-center'>Unit</td>
                    <td class="text-center">
                      <div class="flex space-x-2">
                          <button class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center">
                              <i class="fa-solid fa-check mr-2"></i>Approved
                          </button>   
                          <button class="bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded flex items-center">
                              <i class="fa-regular fa-pen-to-square mr-2"></i>Edit
                          </button>
                      </div>
                  </td>
                  </tr>
                  <!-- More rows as needed -->
                </tbody>
              </table>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/Product.js') }}"></script>
@if(session('success'))
    <div id="success-alert" class="fixed top-0 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg transform -translate-y-full opacity-0 transition-transform duration-500 ease-in-out">
        <div class="flex items-center">
        <img src="/image-Icon/check.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif
<script src="{{ asset('js/alert.js') }}"></script>
@endsection