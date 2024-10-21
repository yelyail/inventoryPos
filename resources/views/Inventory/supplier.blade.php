@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')
<div id="main" class="main-content-flow flex-1 bg-white-100 mt-13 md:mt-2 md:ml-48 pb-28 md:pb-10">
    <div class="w-full flex flex-col flex-grow mt-1">
        <div class="w-full p-3">
            <div class="bg-gray border border-black-950">
                <!-- Header Section with Search Box and Add Button -->
                <div class="flex justify-between items-center uppercase text-gray-800 rounded-tl-lg rounded-tr-lg p-2">
                    <h1 class="prod_title text-2xl font-bold">Supplier</h1>
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
                <div class="md:w-full text-right mt-3 md:mt-0">
                    <button type="button" class="btn-add" id="plus-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="fas fa-sharp fa-plus pr-0 md:pr-3:"></i> Add Supplier
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-850">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Supplier Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Phone Number</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Address</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Email</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-500">John Doe</td>
                            <td class="px-4 py-2 text-sm text-gray-500">johndoe</td>
                            <td class="px-4 py-2 text-sm text-gray-500">Manager</td>
                            <td class="px-4 py-2 text-sm text-gray-500">1234567890</td>
                            <td class="px-4 py-2 text-sm text-gray-500">
                            <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="fas fa-sharp fa-pen-to-square pr-0 md:pr-2"></i><u>Edit</u>
                            </button>
                            <button type="button" class="btn-edit ml-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="fas fa-sharp fa-box-archive pr-0 md:pr-2"></i><u>Archive</u>
                            </button>

                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-500">John Doe</td>
                            <td class="px-4 py-2 text-sm text-gray-500">johndoe</td>
                            <td class="px-4 py-2 text-sm text-gray-500">Manager</td>
                            <td class="px-4 py-2 text-sm text-gray-500">1234567890</td>
                            <td class="px-4 py-2 text-sm text-gray-500">
                            <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="fas fa-sharp fa-pen-to-square pr-0 md:pr-3:"></i> Edit
                            </button>
                        </tr>
                         <tr>
                            <td class="px-4 py-2 text-sm text-gray-500">John Doe</td>
                            <td class="px-4 py-2 text-sm text-gray-500">johndoe</td>
                            <td class="px-4 py-2 text-sm text-gray-500">Manager</td>
                            <td class="px-4 py-2 text-sm text-gray-500">1234567890</td>
                            <td class="px-4 py-2 text-sm text-gray-500">
                            <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="fas fa-sharp fa-pen-to-square pr-0 md:pr-3:"></i> Edit
                            </button>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@section('content')
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