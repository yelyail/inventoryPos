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
        <div class="w-full p-5">
            <div class="bg-gray">
                <!-- Header Section with Search Box and Add Button -->
                <div class="flex justify-between items-center uppercase text-gray-800 rounded-tl-lg rounded-tr-lg p-2">
                    <h1 class="prod_title text-2xl font-bold ">User Information</h1>
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
                    <button type="button" onclick="openAddUserModal()" class="btn-add" id="plus-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="fas fa-sharp fa-plus pr-0 md:pr-3:"></i> Add User
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-850 custom-table" id="userTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Employee Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Username</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Job Role</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Phone Number</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ ucwords(strtolower($user->fullname)) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ ucwords(strtolower($user->username)) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $user->job_title }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ '+63 ' . substr($user->phone_number, 0, 3) . ' ' . substr($user->phone_number, 3, 3) . ' ' . substr($user->phone_number, 6) }}</td>
                                <td class="text-center">
                                    <div class="flex space-x-2">
                                        @if($user->archived == 0)
                                            <button class="bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded flex items-center" 
                                                    onclick="event.stopPropagation();">
                                                <i class="fa-regular fa-pen-to-square mr-2"></i>Edit
                                            </button>   
                                            <button class="bg-gray-500 hover:bg-gray-700 text-white px-2 py-1 rounded flex items-center" 
                                                    onclick="event.stopPropagation(); userArchive('{{ $user->user_id }}', this)">
                                                <i class="fa-solid fa-box-archive mr-2"></i>Archived
                                            </button>
                                            @else
                                                <span class="badge bg-gray-500 hover:bg-gray-700 text-white px-2 py-1 rounded flex items-center">Inactive</span>
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
<!-- Modal for Adding New User -->
<div id="staticBackdropUser" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-labelledby="staticBackdropUserLabel">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
        <div class="flex justify-between items-center p-4 border-b">
            <h1 class="text-lg font-semibold" id="staticBackdropUserLabel">Add Employee</h1>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeUserModal()">
                &times; <!-- Close button -->
            </button>
        </div>
        <div class="p-4">
            <form id="inventoryForm" action="{{route('registerSave') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="fullname" class="block text-sm font-medium text-gray-700">Employee Name</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="fullname" name="fullname" placeholder="Enter employee name" required>
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="username" name="username" placeholder="Enter username" required>
                </div>
                <div class="mb-4">
                    <label for="job_title" class="block text-sm font-medium text-gray-700">Job Title</label>
                    <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="job_title" name="job_title" required>
                        <option value="" disabled selected hidden>Job Title</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="officeStaff">Office Staff</option>
                        <option value="technician">Technician</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="phone_number" name="phone_number" placeholder="Enter Phone Number" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="password" name="password" placeholder="********" required>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn bg-green-500 text-white hover:bg-green-600">Save</button>
                    <button type="button" class="btn bg-red-500 text-white hover:bg-red-600 ml-2" onclick="closeUserModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script> 

    function openAddUserModal() {
        document.getElementById('staticBackdropUser').classList.remove('hidden');
    }

    function closeUserModal() {
        document.getElementById('staticBackdropUser').classList.add('hidden');
    }
    document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
});
</script>

@endsection
