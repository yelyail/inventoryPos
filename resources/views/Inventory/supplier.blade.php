@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
@if(session('success'))
    <div id="success-alert" class="fixed top-4 left-0 right-0 mx-auto w-full max-w-md p-4 bg-white text-black rounded shadow-lg transform translate-y-10 opacity-100 transition-transform duration-500 ease-in-out">
        <div class="flex items-center">
            <img src="/image-Icon/check.gif" alt="" style="width:40px; margin-right: 10px;">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

<div id="main" class="main-content-flow flex-1 bg-white-100 mt-13 md:mt-2 md:ml-60 pb-28 md:pb-10">
    <div class="w-full flex flex-col flex-grow mt-1">
        <div class="w-full p-3">
            <div class="bg-gray">
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
                                <i class="fas fa-sharp fa-magnifying-glass pr-0 md:pr-3"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control p-2 rounded-l-md" placeholder="Search..." aria-label="Search">
                            <button class="btn custom-btn p-2 rounded-r-md" type="button" onclick="filterTable()">Search</button>
                        </div>
                    </div>
                </div>
                <div class="md:w-full text-right mt-3 md:mt-0">
                    <button type="button" class="btn-add" id="plus-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openAddSupplierModal()">
                        <i class="fas fa-sharp fa-plus pr-0 md:pr-3"></i> Add Supplier
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-850 custom-table">
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
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ ucwords(strtolower($supplier->supplier_name)) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ '+63 ' . substr($supplier->supplier_phone, 0, 3) . ' ' . substr($supplier->supplier_phone, 3, 3) . ' ' . substr($supplier->supplier_phone, 6) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ ucwords(strtolower($supplier->supplier_address)) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $supplier->supplier_email }}</td>
                                <td class="text-center">
                                    <div class="flex space-x-2">
                                        @if($supplier->status == 0)
                                            <button class="bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded flex items-center" 
                                                onclick="event.stopPropagation(); openEditSupplierModal('{{ $supplier->supplier_ID }}', '{{ addslashes($supplier->supplier_name) }}', '{{ addslashes($supplier->supplier_phone) }}', '{{ addslashes($supplier->supplier_address) }}', '{{ $supplier->supplier_email }}')">
                                                <i class="fa-regular fa-pen-to-square mr-2"></i>Edit
                                            </button>
                                            <button class="bg-gray-500 hover:bg-gray-700 text-white px-2 py-1 rounded flex items-center" 
                                                    onclick="event.stopPropagation(); supplierArchive('{{ $supplier->supplier_ID }}', this)">
                                                <i class="fa-solid fa-box-archive mr-2"></i>Archived
                                            </button>
                                        @else
                                            <span class="badge bg-gray-500 text-white px-2 py-1 rounded flex items-center">Inactive</span>
                                        @endif
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
<!-- Modal for editing a user -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="editSupplierModal" tabindex="-1" >
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold">Update Supplier</h2>
                <button type="button" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700" data-bs-dismiss="modal" aria-label="Close">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div class="px-6 py-4">
                <form id="editSupplierForm" action="{{ route('editSupplier') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" id="editSupplierId" name="id">
                    <div class="mb-4">
                        <label for="editSupplierName" class="block text-sm font-medium text-gray-700">Supplier Name</label>
                        <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" id="editSupplierName" name="supplier_name" required>
                        <div class="text-red-500 text-sm mt-1 hidden" id="editSupplierNameFeedback">Please enter the supplier's name.</div>
                    </div>
                    <div class="mb-4">
                        <label for="editPhoneNumber" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" id="editPhoneNumber" name="supplier_phone" pattern="[0-9]{10}" required>
                        <div class="text-red-500 text-sm mt-1 hidden" id="editPhoneNumberFeedback">Please enter a valid phone number.</div>
                    </div>
                    <div class="mb-4">
                        <label for="editSupplierAddress" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" id="editSupplierAddress" name="supplier_address" required>
                        <div class="text-red-500 text-sm mt-1 hidden" id="editSupplierAddressFeedback">Please enter an address.</div>
                    </div>
                    <div class="mb-4">
                        <label for="editSupplierEmail" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" id="editSupplierEmail" name="supplier_email" required>
                        <div class="text-red-500 text-sm mt-1 hidden" id="editSupplierEmailFeedback">Please enter a valid email.</div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md" onclick="closeEditSupplierModal()">Close</button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Adding New Supplier -->
<div id="staticBackdropSupplier" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-labelledby="staticBackdropSupplierLabel" aria-hidden="true">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
        <div class="flex justify-between items-center p-4 border-b">
            <h1 class="text-lg font-semibold" id="staticBackdropSupplierLabel">Add Supplier</h1>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeSupplierModal()">
                &times; <!-- Close button -->
            </button>
        </div>
        <div class="p-4">
            <form id="supplierForm" action="{{ route('storeSupplier') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="supplierName" class="block text-sm font-medium text-gray-700">Supplier Name</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="supplierName" name="supplier_name" placeholder="Enter supplier name" required>
                </div>
                <div class="mb-4">
                    <label for="supplier_contact" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="supplier_contact" name="supplier_contact" pattern="\d{10}" maxlength="10" placeholder="9123456789" title="Phone number should only contain 10 digits (without country code)" required>
                </div>
                <div class="mb-4">
                    <label for="supplier_address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="supplier_address" name="supplier_address" placeholder="Enter Address Name" required>
                </div>
                <div class="mb-4">
                    <label for="supplier_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="supplier_email" name="supplier_email" placeholder="Enter Email" required>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn bg-green-500 text-white hover:bg-green-600">Save</button>
                    <button type="button" class="btn bg-red-500 text-white hover:bg-red-600 ml-2" onclick="closeSupplierModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function openAddSupplierModal() {
        document.getElementById('staticBackdropSupplier').classList.remove('hidden');
    }

    function closeSupplierModal() {
        document.getElementById('staticBackdropSupplier').classList.add('hidden');
    }
    function filterTable() {
        let input = document.getElementById('searchInput');
        let filter = input.value.toLowerCase();
        let table = document.querySelector('.custom-table');
        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < td.length; j++) {
                if (td[j] && td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }

            tr[i].style.display = found ? '' : 'none';
        }
    }
</script>
@endsection