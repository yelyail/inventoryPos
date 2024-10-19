@extends('inventory.side')

@section('title', 'DAVCOM Consumer Goods Trading')

@section('content')
<div id="main" class="main-content flex-1 bg-gray-100 mt-12 md:mt-0 md:ml-48 pb-24 md:pb-5"> 
  <div class="flex flex-row flex-wrap flex-grow mt-2">
    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
      <div class="bg-gray border border-gray-300 rounded-lg">
        <div class="uppercase text-gray-800 border-b-2 border-gray-800 rounded-tl-lg rounded-tr-lg p-2">
          <h2 class="font-bold uppercase text-gray-600">Low Stock Product</h2>
        </div>
        <div class="p-5">
          <table class="w-full p-5 text-gray-700">
                                <thead>
                                <tr>
                                    <th class="text-left text-gray-600">Serial #</th>
                                    <th class="text-left text-gray-600">Category Name</th>
                                    <th class="text-left text-gray-600">Brand Name</th>
                                    <th class="text-left text-gray-600">Product Name</th>
                                    <th class="text-left text-gray-600">Unit</th>
                                    <th class="text-left text-gray-600">Stocks</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
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
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <div class="bg-gray border border-gray-300 rounded-lg">
                        <div class="uppercase text-gray-800 border-b-2 border-gray-800 rounded-tl-lg rounded-tr-lg p-2">
                            <h2 class="font-bold uppercase text-gray-600">Defective Product</h2>
                        </div>
                        <div class="p-5">
                            <table class="w-full p-5 text-gray-700">
                                <thead>
                                <tr>
                                    <th class="text-left text-gray-600">Serial #</th>
                                    <th class="text-left text-gray-600">Category Name</th>
                                    <th class="text-left text-gray-600">Brand Name</th>
                                    <th class="text-left text-gray-600">Product Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
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
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <div class="bg-gray border border-gray-300 rounded-lg">
                        <div class="uppercase text-gray-800 border-b-2 border-gray-800 rounded-tl-lg rounded-tr-lg p-2">
                            <h2 class="font-bold uppercase text-gray-600">Products Pending Inspection</h2>
                        </div>
                        <div class="p-5">
                            <table class="w-full p-5 text-gray-700">
                                <thead>
                                <tr>
                                    <th class="text-left text-gray-600">Serial #</th>
                                    <th class="text-left text-gray-600">Category Name</th>
                                    <th class="text-left text-gray-600">Brand Name</th>
                                    <th class="text-left text-gray-600">Product Name</th>
                                    <th class="text-left text-gray-600">Days Arrived</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
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
    </div>
</main>

<script>
    function toggleDropdown(dropdownId) {
        document.getElementById(dropdownId).classList.toggle("hidden");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.block')) {
            var dropdowns = document.querySelectorAll('.absolute');
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.add('hidden');
            });
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