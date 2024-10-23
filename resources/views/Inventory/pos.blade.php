@extends('inventory.side')

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
              <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white text-gray-900 transition duration-150 ease-in-out hover:bg-gray-100" id="category" name="categoryName" >
                <option value="" disabled selected>All Items</option>
                  @foreach($categories as $category)
                      <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                  @endforeach
              </select>
          </div>
          <!-- end categories -->
          <!-- products -->
          <div class="grid grid-cols-3 gap-4 px-5 mt-5 overflow-y-auto h-3/4">
            @foreach($products as $product)
                <div class="relative px-3 py-3 flex flex-col border border-gray-200 rounded-md h-32">
                    <div class="flex-grow">
                        <div class="font-bold text-gray-800">{{ ucwords(strtolower($product->model_name)) }}</div>
                        <span class="font-light text-sm text-gray-400">{{ $product->brand_name }}</span>
                    </div>
                    <img src="{{ asset("storage/{$product->product_image}") }}" class="absolute top-2 right-2 h-14 w-14 object-cover rounded-md" alt="{{ $product->model_name }}">
                    <div class="flex justify-between items-center mt-auto">
                        <span class="font-bold text-lg text-gray-500">${{ number_format($product->unitPrice, 2) }}</span>
                        <button class="bg-blue-500 text-white px-2 py-1 hover:bg-blue-600 rounded"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            @endforeach
          </div>
          <!-- end products -->
        </div>
        <!-- end left section -->
        <!-- right section -->
        <div class="w-full lg:w-2/5">
          <!-- header -->
          <div class="flex flex-row items-center justify-between px-5 mt-5">
            <div class="font-bold text-xl">Order Summary</div>
          </div>
          <!-- end header -->
          <!-- order list -->
          <div class="px-5 py-4 mt-5 overflow-y-auto h-64">
              <div class="flex flex-row justify-between items-center mb-4">
                <div class="flex flex-row items-center w-2/5">
                  <img src="https://source.unsplash.com/4u_nRgiLW3M/600x600" class="w-10 h-10 object-cover rounded-md" alt="">
                  <span class="ml-4 font-semibold text-sm">Stuffed flank steak</span>
                </div>
                <div class="w-32 flex justify-between">
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">-</span>
                  <span class="font-semibold mx-4">2</span>
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                </div>
                <div class="font-semibold text-lg w-16 text-center">
                  $13.50
                </div>
              </div>             
              <div class="flex flex-row justify-between items-center mb-4">
                <div class="flex flex-row items-center w-2/5">
                  <img src="https://source.unsplash.com/sc5sTPMrVfk/600x600" class="w-10 h-10 object-cover rounded-md" alt="">
                  <span class="ml-4 font-semibold text-sm">Grilled Corn</span>
                </div>
                <div class="w-32 flex justify-between">
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">-</span>
                  <span class="font-semibold mx-4">10</span>
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                </div>
                <div class="font-semibold text-lg w-16 text-center">
                  $3.50
                </div>
              </div>
              <div class="flex flex-row justify-between items-center mb-4">
                <div class="flex flex-row items-center w-2/5">
                  <img src="https://source.unsplash.com/MNtag_eXMKw/600x600" class="w-10 h-10 object-cover rounded-md" alt="">
                  <span class="ml-4 font-semibold text-sm">Grilled Corn</span>
                </div>
                <div class="w-32 flex justify-between">
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">-</span>
                  <span class="font-semibold mx-4">10</span>
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                </div>
                <div class="font-semibold text-lg w-16 text-center">
                  $3.50
                </div>
              </div>
              <div class="flex flex-row justify-between items-center mb-4">
                <div class="flex flex-row items-center w-2/5">
                  <img src="https://source.unsplash.com/MNtag_eXMKw/600x600" class="w-10 h-10 object-cover rounded-md" alt="">
                  <span class="ml-4 font-semibold text-sm">Grilled Corn</span>
                </div>
                <div class="w-32 flex justify-between">
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">-</span>
                  <span class="font-semibold mx-4">10</span>
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                </div>
                <div class="font-semibold text-lg w-16 text-center">
                  $3.50
                </div>
              </div> 
              <div class="flex flex-row justify-between items-center mb-4">
                <div class="flex flex-row items-center w-2/5">
                  <img src="https://source.unsplash.com/MNtag_eXMKw/600x600" class="w-10 h-10 object-cover rounded-md" alt="">
                  <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                </div>
                <div class="w-32 flex justify-between">
                  <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                  <span class="font-semibold mx-4">1</span>
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                </div>
                <div class="font-semibold text-lg w-16 text-center">
                  $2.50
                </div>
              </div> 
              <div class="flex flex-row justify-between items-center mb-4">
                <div class="flex flex-row items-center w-2/5">
                  <img src="https://source.unsplash.com/4u_nRgiLW3M/600x600" class="w-10 h-10 object-cover rounded-md" alt="">
                  <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                </div>
                <div class="w-32 flex justify-between">
                  <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                  <span class="font-semibold mx-4">1</span>
                  <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                </div>
                <div class="font-semibold text-lg w-16 text-center">
                  $2.50
                </div>
              </div>            
          </div>
          <!-- end order list -->
          <!-- totalItems -->
          <div class="px-5 mt-5">
            <div class="py-4 rounded-md shadow-lg">
              <div class=" px-4 flex justify-between ">
                <span class="font-semibold text-sm">Subtotal</span>
                <span class="font-bold">$35.25</span>
              </div>
              <div class=" px-4 flex justify-between ">
                <span class="font-semibold text-sm">Discount</span>
                <span class="font-bold">- $5.00</span>
              </div>
              <div class=" px-4 flex justify-between ">
                <span class="font-semibold text-sm">Sales Tax</span>
                <span class="font-bold">$2.25</span>
              </div>
              <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                <span class="font-semibold text-2xl">Total</span>
                <span class="font-bold text-2xl">$37.50</span>
              </div>
            </div>
          </div>
          <!-- end total -->
          <!-- cash -->
          <div class="px-5 mt-5">
            <div class="rounded-md shadow-lg px-4 py-4">
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-row space-x-4">
                        <!-- Cash Checkbox -->
                        <label class="flex items-center">
                            <input type="checkbox" class="invisible-checkbox" id="cashCheckbox" />
                            <span class="bg-white text-blue-500 px-4 py-2 rounded-md hover:bg-blue-100 flex items-center">
                                <i class="fa-regular fa-money-bill-1 mr-1"></i> Cash
                            </span>
                        </label>
                        <!-- GCash Checkbox -->
                        <label class="flex items-center">
                            <input type="checkbox" class="invisible-checkbox" id="gcashCheckbox"/>
                            <span class="bg-white text-blue-500 px-4 py-2 rounded-md hover:bg-blue-100 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 100 100" class="mr-1">
                                    <path d="M 43 14 C 23.158623 14 7 30.158623 7 50 C 7 69.841377 23.158623 86 43 86 C 51.060131 86 58.705597 83.380252 65.068359 78.435547 C 67.239949 76.748053 67.633291 73.589271 65.947266 71.417969 C 64.261706 69.246908 61.103484 68.850128 58.931641 70.539062 C 54.327496 74.117279 48.832876 76 43 76 C 28.654361 76 17 64.345639 17 50 C 17 35.654361 28.654361 24 43 24 C 48.832876 24 54.327526 25.883734 58.931641 29.462891 C 61.102894 31.15109 64.26156 30.75328 65.947266 28.582031 C 67.635318 26.410966 67.239365 23.251215 65.068359 21.564453 C 58.705556 16.619716 51.059159 14 43 14 z M 43 16 C 50.626841 16 57.818647 18.465268 63.839844 23.144531 C 65.156838 24.16777 65.393088 26.036581 64.369141 27.353516 L 64.367188 27.355469 C 63.34494 28.672173 61.476903 28.906613 60.160156 27.882812 C 55.214271 24.037969 49.265124 22 43 22 C 27.569639 22 15 34.569639 15 50 C 15 65.430361 27.569639 78 43 78 C 49.265124 78 55.214301 75.960971 60.160156 72.117188 C 61.478313 71.092122 63.344746 71.327592 64.367188 72.644531 C 65.391161 73.963229 65.158254 75.830962 63.839844 76.855469 C 57.818606 81.534764 50.627869 84 43 84 C 24.243377 84 9 68.756623 9 50 C 9 31.243377 24.243377 16 43 16 z M 82.189453 25.998047 C 81.563233 25.998047 80.941132 26.148176 80.371094 26.439453 C 78.411676 27.441972 77.630256 29.862773 78.632812 31.822266 C 81.532591 37.491407 83 43.601602 83 50 C 83 56.400473 81.532561 62.50964 78.632812 68.177734 C 78.146552 69.126341 78.059863 70.218014 78.386719 71.230469 C 78.714562 72.243596 79.423648 73.075437 80.371094 73.560547 C 80.93947 73.851556 81.556115 74.001953 82.189453 74.001953 C 83.69502 74.001953 85.069567 73.163486 85.753906 71.822266 L 85.753906 71.820312 C 89.234649 65.020884 91 57.673995 91 50 C 91 42.328371 89.235048 34.980538 85.755859 28.177734 C 85.270792 27.229234 84.435988 26.521154 83.421875 26.193359 C 83.018972 26.063611 82.603939 25.998047 82.189453 25.998047 z M 82.189453 26.998047 C 82.498967 26.998047 82.810138 27.046277 83.115234 27.144531 C 83.883121 27.392737 84.498302 27.915313 84.865234 28.632812 C 88.274046 35.298009 90 42.481629 90 50 C 90 57.521401 88.273995 64.703158 84.863281 71.365234 L 84.863281 71.367188 C 84.347391 72.379192 83.327389 73.001953 82.189453 73.001953 C 81.710791 73.001953 81.257796 72.892866 80.826172 72.671875 C 80.109617 72.304985 79.586047 71.688747 79.337891 70.921875 C 79.090746 70.15633 79.155697 69.352159 79.523438 68.634766 L 79.523438 68.632812 C 82.493689 62.826907 84 56.553527 84 50 C 84 43.448398 82.491706 37.174045 79.521484 31.367188 C 78.766042 29.89068 79.34959 28.085559 80.826172 27.330078 C 81.258134 27.109355 81.721673 26.998047 82.189453 26.998047 z M 43 29 C 31.424828 29 22 38.424828 22 50 C 22 61.575172 31.424828 71 43 71 C 54.575172 71 64 61.575172 64 50 C 64 47.798843 62.201157 46 60 46 L 48 46 C 45.798843 46 44 47.798843 44 50 C 44 52.201157 45.798843 54 48 54 L 55.369141 54 C 53.701918 59.387459 48.658785 63 43 63 C 35.82718 63 30 57.17282 30 50 C 30 42.82718 35.82718 37 43 37 C 45.755221 37 48.392658 37.864469 50.642578 39.505859 L 50.642578 39.503906 C 51.503111 40.131924 52.568046 40.389458 53.617188 40.226562 C 54.669756 40.062526 55.603047 39.490576 56.230469 38.630859 C 57.528145 36.851971 57.136312 34.340646 55.357422 33.042969 C 51.736037 30.400162 47.626176 29 43 29 z" fill="#0D6EFD"></path>
                                </svg> GCash
                            </span>
                        </label>
                    </div>
                </div>
                <!-- Cash Name Input Field -->
                <div id="cashNameDiv" class="mt-4 hidden">
                    <label for="cashName" class="block text-gray-700">Enter Name</label>
                    <input type="text" id="cashName" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                </div>
                <!-- Cash Address Input Field -->
                <div id="cashAddressDiv" class="mt-4 hidden">
                    <label for="cashAddress" class="block text-gray-700">Enter Address</label>
                    <input type="text" id="cashAddress" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                </div>
                <!-- Cash Amount Input Field -->
                <div id="cashAmountDiv" class="mt-4 hidden">
                    <label for="cashAmount" class="block text-gray-700">Enter Cash Amount</label>
                    <input type="number" id="cashAmount" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                </div>
                <!-- gcash -->
                <div id="gcashNameDiv" class="mt-4 hidden">
                    <label for="gcashName" class="block text-gray-700">Enter Name</label>
                    <input type="text" id="gcashName" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                </div>
                <!-- Cash Address Input Field -->
                <div id="gcashAddressDiv" class="mt-4 hidden">
                    <label for="gcashAddress" class="block text-gray-700">Enter Address</label>
                    <input type="text" id="gcashAddress" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                </div>
                <!-- Cash Amount Input Field -->
                <div id="gcashReferenceDiv" class="mt-4 hidden">
                    <label for="gcashReference" class="block text-gray-700">Reference Number</label>
                    <input type="number" id="gcashReference" class="mt-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                </div>
            </div>
          </div>
          <!-- end cash -->
          <!-- button pay-->
          <div class="px-5 mt-5">
            <button class="confirm px-12 py-4 rounded-md shadow-lg text-center bg-green-500 text-white font-semibold focus:outline-none">
                Confirm Payment
            </button>
          </div>

          <!-- end button pay -->
        </div>
        <!-- end right section -->
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
<script>
  // gcash checkbox
  document.getElementById('gcashCheckbox').addEventListener('change', function() {
      const gcashNameDiv = document.getElementById('gcashNameDiv');
      const gcashAddressDiv = document.getElementById('gcashAddressDiv');
      const gcashReferenceDiv = document.getElementById('gcashReferenceDiv');
      const cashAmountDiv = document.getElementById('cashAmountDiv');
      const cashAddressDiv = document.getElementById('cashAddressDiv');
      const cashNameDiv = document.getElementById('cashNameDiv');
      const cashCheckbox = document.getElementById('cashCheckbox');

      if (this.checked) {
          // Uncheck cash checkbox if GCash is checked
          cashCheckbox.checked = false;

          // Hide cash fields
          cashAmountDiv.classList.add('hidden');
          cashAddressDiv.classList.add('hidden');
          cashNameDiv.classList.add('hidden');
          document.getElementById('cashAmount').value = '';
          document.getElementById('cashName').value = '';
          document.getElementById('cashAddress').value = '';

          // Show GCash fields
          gcashNameDiv.classList.remove('hidden');
          gcashAddressDiv.classList.remove('hidden');
          gcashReferenceDiv.classList.remove('hidden');
      } else {
          // Hide GCash fields when unchecked
          gcashNameDiv.classList.add('hidden');
          gcashAddressDiv.classList.add('hidden');
          gcashReferenceDiv.classList.add('hidden');
          document.getElementById('gcashName').value = '';
          document.getElementById('gcashAddress').value = '';
          document.getElementById('gcashReference').value = '';
      }
  });

  // cash checkbox
  document.getElementById('cashCheckbox').addEventListener('change', function() {
      const cashAmountDiv = document.getElementById('cashAmountDiv');
      const cashAddressDiv = document.getElementById('cashAddressDiv');
      const cashNameDiv = document.getElementById('cashNameDiv');
      const gcashCheckbox = document.getElementById('gcashCheckbox');
      const gcashNameDiv = document.getElementById('gcashNameDiv');
      const gcashAddressDiv = document.getElementById('gcashAddressDiv');
      const gcashReferenceDiv = document.getElementById('gcashReferenceDiv');

      if (this.checked) {
          // Uncheck GCash checkbox if cash is checked
          gcashCheckbox.checked = false;

          // Hide GCash fields
          gcashNameDiv.classList.add('hidden');
          gcashAddressDiv.classList.add('hidden');
          gcashReferenceDiv.classList.add('hidden');
          document.getElementById('gcashName').value = '';
          document.getElementById('gcashAddress').value = '';
          document.getElementById('gcashReference').value = '';

          // Show cash fields
          cashAmountDiv.classList.remove('hidden');
          cashAddressDiv.classList.remove('hidden');
          cashNameDiv.classList.remove('hidden');
      } else {
          // Hide cash fields when unchecked
          cashAmountDiv.classList.add('hidden');
          cashAddressDiv.classList.add('hidden');
          cashNameDiv.classList.add('hidden');
          document.getElementById('cashAmount').value = '';
          document.getElementById('cashName').value = '';
          document.getElementById('cashAddress').value = '';
      }
  });
</script>


@endsection
